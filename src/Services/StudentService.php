<?php

namespace App\Services;

use App\Entity\Classroom;
use App\Entity\Feedback;
use App\Entity\Professor;
use App\Entity\ProfessorAttendance;
use App\Entity\Student;
use App\Entity\StudentAttendance;
use App\Entity\Subjects;
use App\Entity\University;
use App\Entity\User;
use App\Repository\FeedbackRepository;
use App\Repository\ProfessorRepository;
use App\Repository\StudentRepository;
use App\Repository\UserRepository;
use App\Services\Admin\AdminService;
use App\Services\Classes\ClassService;
use Symfony\Component\HttpFoundation\Response;
use DateTime;
use Twig\Environment;

class StudentService extends AbstractService
{
    private $studentRepository;
    private $professorRepository;
    private $twig;
    private $classroomService;
    private $instituteService;
    private $scheduleService;
    private $professorAttendanceService;
    private $studentAttendanceService;
    private $subjectService;
    private $userRepo;
    private $feedbcakRepo;

    public function __construct(StudentRepository        $repository, Environment $twig, ClassService $classroomService,
                                AdminService             $adminService, ScheduleService $scheduleService, ProfessorAttendanceService $pas,
                                StudentAttendanceService $sar, ProfessorRepository $professorRepository,
                                MateriiService           $subjectService, UserRepository $ur, FeedbackRepository $feedbcakRepo)
    {
        parent::__construct($repository);

        $this->studentRepository = $repository;
        $this->twig = $twig;
        $this->classroomService = $classroomService;
        $this->instituteService = $adminService;
        $this->scheduleService = $scheduleService;
        $this->professorAttendanceService = $pas;
        $this->studentAttendanceService = $sar;
        $this->professorRepository = $professorRepository;
        $this->subjectService = $subjectService;
        $this->userRepo = $ur;
        $this->feedbcakRepo = $feedbcakRepo;
    }

    public function studentProfile($request, User $user): Response
    {
        /** @var Student $student */
        $student = $this->studentRepository->findOneBy(['user' => $user->getId()]);
        if ($request->isMethod('POST')) {
            $code = $request->request->get('cod');
            /** @var Classroom $classroom */
            $classroom = $this->classroomService->getOneBy(['cod' => $code]);
            $institute = $this->instituteService->getOneBy(['id' => $classroom->getIdUniv()]);
            if ($classroom) {
                //introduc student
                $nrStud = $classroom->getNrStudents();
                $nrStud++;
                $classroom->setNrStudents($nrStud);
                $this->classroomService->repository->flush();
                $student->setInstitute($institute);
                $student->setClassroom($classroom);
                $this->studentRepository->persist($student);
                $this->studentRepository->flush();

                /** @var University $univ */
                $univ = $this->instituteService->getOneBy(['id'=>$classroom->getIdUniv()]);

                $content = $this->twig->render('students/profile.html.twig',
                ['class'=>$classroom->getNume(),
                    'univ'=>$univ->getNumeleUniversitatii(),
                    'faculty'=>$univ->getNumeleFacultatii(),
                    'email' => $univ->getEmail(),
                    'nrTel' =>$univ->getNrTel()
                    ]);
                return new Response($content);
            } else {
                $content = $this->twig->render('students/cod-univ.html.twi',
                    ['error_invalid' => 'invalid']);
                return new Response($content);
            }
        }
        if ($student->getClassroom()) {
            $class = $student->getClassroom();
            /** @var Classroom $class */
            $class = $this->classroomService->getOneBy(['id'=>$student->getClassroom()]);
            $className = $class->getNume();
            /** @var University $univ */
            $univ = $this->instituteService->getOneBy(['id'=>$class->getIdUniv()]);
            $content = $this->twig->render('students/profile.html.twig',
            ['class'=>$className,
                'univ'=>$univ->getNumeleUniversitatii(),
                'faculty'=>$univ->getNumeleFacultatii(),
                'email' => $univ->getEmail(),
                'nrTel' =>$univ->getNrTel()
            ]);
            return new Response($content);
        }
        $content = $this->twig->render('students/cod-univ.html.twig',
            ['error_invalid' => null]);
        return new Response($content);
    }


    public function studentSchedule(\Symfony\Component\HttpFoundation\Request $request, User $user)
    {
        //user are un id
        //student are user_id si classroom_id
        //iau orarul in fct de id_class

        /** @var Student $student */
        $student = $this->getOneBy(['user' => $user]);
        /** @var Classroom $classroom */
        $classroom = $student->getClassroom();
        $classroomId = $classroom->getId();
        /** @var Classroom $classroom */
        $classroom = $this->classroomService->getOneBy(['id' => $classroomId]);
        $classroom = $classroom->getNume();
        $schedule = $this->scheduleService->getBy(['idClass' => $classroomId]);
        dump($schedule);
        $content = $this->twig->render('students/schedule.html.twig',
            ['schedule' => $schedule,
                'classroom_name' => $classroom]);
        return new Response($content);
    }

    public function studentAttendance(\Symfony\Component\HttpFoundation\Request $request, User $user)
    {
        $userId = $user->getId();
        /** @var Student $student */
        $student = $this->studentRepository->findOneBy(['user' => $userId]);
        $classroomId = $student->getClassroom();
        $attendances = $this->professorAttendanceService->getBy(['classroomId' => $classroomId]);
        foreach ($attendances as $atte) {
            /**@var \App\Entity\ProfessorAttendance $atte */
            $atteId = $atte->getId();
            $alreadyPresent = $this->studentAttendanceService->getBy(['attendanceId' => $atteId, 'studentId' => $user->getId()]);
            if (!$alreadyPresent) {
                $subjectId = $atte->getSubjectId();
                $createdAt = $atte->getCreatedAt();
                $prof = $atte->getProfessorId();
                /** @var User $prof */
                $prof = $this->userRepo->findOneBy(['id' => $prof]);
                $profName = $prof->getLastName();
                $profName = $profName . ' ' . $prof->getFirstName();
                /** @var DateTime $createdAt */
                $date = $createdAt->format('d.n.o'); // day month year ex: 24.6.2021
                $subject = $atte->getSubjectId();
                /** @var Subjects $subject */
                $subject = $this->subjectService->getOneBy(['id' => $subject]);
                $subject = $subject->getNume();

                //sa introduc prezenta in baza de date la post
                $studAtt = $this->studentAttendanceService->getOneBy(['studentId' => $user->getId(), 'attendanceId' => $atte->getId()]);
                if (!$studAtt) {
                    //"absent la aceasta ora";
                    if ($request->isMethod('POST')) {
                        $attendance = new StudentAttendance();
                        $attendance->setCreatedAt();
                        $attendance->setAttendanceId($atteId);
                        $attendance->setStudentId($user->getId());
                        $lat = $request->get('lat'); //Student location, may be null
                        $lon = $request->get('lon'); //Student location, may be null
                        $attendance->setLatitude($lat);
                        $attendance->setLongitude($lon);
                        /** @var ProfessorAttendance $atte */
                        $dist = $this->getDistance(floatval($lat), floatval($lon), floatval($atte->getLatitude()), floatval($atte->getLongitude()));
                        $attendance->setDistance($dist);
                        if ($dist >= 10)
                            $attendance->setDistanceStatus("Invalid");
                        else
                            $attendance->setDistanceStatus("Valid");
                        if ($lat === "" && $lon === "")
                            $attendance->setLocationExposure('ascunsa');
                        else
                            $attendance->setLocationExposure('expusa');
                        $this->studentAttendanceService->repository->persist($attendance);
                        $this->studentAttendanceService->repository->flush();
                    }
                    else {
                        $content = $this->twig->render('students/attendance.html.twig',
                            ['prof_name' => $profName,
                                'subject' => $subject,
                                'date' => $date]);
                        return new Response($content);
                    }
                }
            }
        }

        $content = $this->twig->render('students/attendance.html.twig',
            ['prof_name' => null,
                'subject' => null,
                'date' => null]);
        return new Response($content);
    }

    // Using Haversine Formula (meters)
    private function getDistance($lat1, $lon1, $lat2, $lon2)
    {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lon1 *= $pi80;
        $lat2 *= $pi80;
        $lon2 *= $pi80;
        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat / 2) * sin($dlat / 2) +
            cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;
        return $km*1000;
    }

    public function deleteStudent($id)
    {
        /** @var Student $student */
        $student = $this->studentRepository->findOneBy(['id'=>$id]);

        dump($student);
        /** @var Classroom $class */
        $class = $student->getClassroom();
        $class = $class->getId();
        $class = $this->classroomService->getOneBy(['id'=>$class]);

        $nrStud = $class->getNrStudents();
        $nrStud --;
        $class->setNrStudents($nrStud);
        $this->classroomService->repository->flush();

        //it should exist as a student in table, but the classroomId and univId should be null

        $student->setClassroom(null);
        $student->setInstitute(null);
        $this->studentRepository->flush();
    }

    public function studentFeedback($request,User $getUser):Response
    {
        $idStudent = $getUser->getId();

        $allAttendances = $this->studentAttendanceService->getBy(['studentId'=> $idStudent]);

        $feedbackList = [];

        dump($allAttendances); //toate prezentele Roxanei

        /** @var StudentAttendance $attendance */
        foreach ($allAttendances as $attendance)
        {
            /** @var Feedback $feedback */
            $feedback = $this->feedbcakRepo->findOneBy(['studentAttendance'=>$attendance->getId()]);
            //daca $feedback e null inseamna ca stud e prezent si nu a primit feedback

            if ($feedback ) { // if the student has feedback for this attendance
                    $mark = $feedback->getMark();
                    $bonus = $feedback->getBonus();
                    $remark = $feedback->getRemark();
                    /** @var DateTime $date */
                    $date = $feedback->getCreatedAt();
                    $date = $date->format('d.n.o');

                    $profAttendanceId = $attendance->getAttendanceId();
                    /** @var ProfessorAttendance $profAttendance */
                    $profAttendance = $this->professorAttendanceService->getOneBy(['id' => $profAttendanceId]);

                    /** @var Subjects $subjectName */
                    $subjectName = $this->subjectService->getOneBy(['id' => $profAttendance->getSubjectId()]);
                    if ($subjectName)
                        $subjectName = $subjectName->getNume();
                    else
                        $subjectName = 'Materie stearsa';

                    array_push($feedbackList,
                        [
                            'date' => $date,
                            'mark' => $mark,
                            'remark' => $remark,
                            'bonus' => $bonus,
                            'subjectName' => $subjectName
                        ]);
            }
        }

        $content =  $this->twig->render('students/feedback.html.twig',
            ['feedbackList'=>$feedbackList]);
        return new Response($content);
    }
}