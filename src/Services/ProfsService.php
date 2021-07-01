<?php


namespace App\Services;


use App\Entity\Classroom;
use App\Entity\Feedback;
use App\Entity\Professor;
use App\Entity\ProfessorAttendance;
use App\Entity\StudentAttendance;
use App\Entity\Subjects;
use App\Entity\User;
use App\Repository\FeedbackRepository;
use App\Repository\ProfessorAttendanceRepository;
use App\Repository\ProfessorRepository;
use App\Services\Admin\AdminService;
use App\Services\Classes\ClassService;
use Monolog\DateTimeImmutable;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Twig\Environment;

class ProfsService extends AbstractService
{
    private $twig;
    private $adminService;
    private $materiiService;
    private $router;
    private $classService;
    private $scheduleService;
    private $professorRepository;
    private $professorAttendanceRepository;
    private $studentAttendanceService;
    private $userService;
    private $feedbackRepo;

    public function __construct(Environment $twig, ProfessorRepository $repository, AdminService $adminService,
                                MateriiService $materiiService,RouterInterface $router,
                                ScheduleService $scheduleService, ClassService $classService,
                                ProfessorAttendanceRepository $professorAttendanceRepository,
                                StudentAttendanceService $studentAttendanceService, UserService $userService,
                                FeedbackRepository $feedbackRepo)
    {

        parent::__construct($repository);
        $this->adminService = $adminService;
        $this->materiiService = $materiiService;
        $this->twig = $twig;
        $this->router = $router;
        $this->classService = $classService;
        $this->scheduleService= $scheduleService;
        $this->professorRepository = $repository;
        $this->professorAttendanceRepository = $professorAttendanceRepository;
        $this->studentAttendanceService = $studentAttendanceService;
        $this->userService = $userService;
        $this->feedbackRepo = $feedbackRepo;
    }

    public function profProfilePage($request, User $user):Response
    {
        $materii = $this->materiiService->getBy(['idProfessor'=>$user->getId()]);

        if ($request->isMethod('POST')) {
        $code = $request->request->get('cod');
        $institute = $this->adminService->getOneBy(['codulInstitutiei' => $code]);

        if ($this->adminService->checkUnivCode($code)){
            $id_prof = $user->getId();
            $prof = new Professor();
            $prof->setInstitute($institute);
            $prof->setUser($user);
            $this->repository->persist($prof);
            $this->repository->flush();
            $content =  $this->twig->render('profesori/profile.html.twig',
            ['materii' => $materii]);
            return new Response($content);
        }
        else
        {
            $content =  $this->twig->render('profesori/cod-univ.html.twig',
                ['error_invalid' => 'invalid']);
            return new Response($content);
        }
        }
        if ($this->professorRepository->findBy(['user' => $user->getId()]))
        {
            $content =  $this->twig->render('profesori/profile.html.twig',
                ['materii' => $materii]);
            return new Response($content);
        }
        $content =  $this->twig->render('profesori/cod-univ.html.twig',
            ['error_invalid' => null]);
        return new Response($content);


    }

    public function profEditClass(Request $request, $form, $materie)
    {

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->materiiService->repository->flush();
            return new RedirectResponse($this->router->generate('profs_profile'));
        }
        $content =  $this->twig->render('profesori/edit-subject.html.twig', array(
            'form' => $form->createView()
        ));
        return new Response($content);

    }

    public function schedule(Request $request)
    {
        $classes = $this->classService->repository->findAll();
        $materii = $this->materiiService->repository->findAll();
        $schedule = $this->scheduleService->repository->findAll();
        $content =  $this->twig->render('profesori/schedule.html.twig',
            ['errors' => null,
                'classes' => $classes,
                'materii'=>$materii,
                'schedule'=> $schedule]);
        return new Response($content);
    }

    public function attendance(Request $request, User $user)
    {
        if ($request->isMethod('POST'))
        {
            $attendance = new ProfessorAttendance();
            $attendance->setSubjectId(intval($request->get('materia')));
            $attendance->setClassroomId(intval($request->get('clasa')));
            $attendance->setLatitude($request->get('lat'));
            $attendance->setLongitude($request->get('lon'));

            $attendance->setProfessorId($user->getId());
            $attendance->setCreatedAt();
            $this->professorAttendanceRepository->persist($attendance);
            $this->professorAttendanceRepository->flush();

        }
        $materii = $this->materiiService->repository->findBy(['idProfessor'=>$user->getId()]);
        $classes = $this->classService->repository->findAll();
        $content =  $this->twig->render('profesori/attendance.html.twig'
        ,['classes' => $classes, 'subjects'=>$materii]);
        return new Response($content);
    }

    public function newSubject($request, \Symfony\Component\Form\FormInterface $form, \App\Entity\Subjects $new, User $user)
    {
        $form ->handleRequest($request);
        $materii = $this->repository->findAll();
        if($form->isSubmitted() && $form->isValid())
        {
            $new = $form->getData();
            //TODO sa nu las 1 aici
            $new->setIdInstitutie(1);
            $new->setIdProfessor($user->getId());
            $this->repository->persist($new);
            $this->repository->flush();
            $response = new RedirectResponse('/profs/profile');
            return $response;
        }

        return $this->response('profesori/new-subject.html.twig',$this->twig, [
            'form' => $form->createView(),
            'materii' => $materii
        ]);
    }

    public function profAttendaceList(Request $request, User $user)
    {
        $allAttendances = $this->professorAttendanceRepository->findBy(['professorId'=>$user->getId()]);
        $attendanceList = [];
        foreach ($allAttendances as $attendance)
        {
            /** @var ProfessorAttendance $attendance */
            $classId = $attendance->getClassroomId();
            /** @var Classroom $className */
            $className = $this->classService->getOneBy(['id'=>$classId]);
            $className = $className->getNume();
            /** @var DateTime $date */
            $date = $attendance->getCreatedAt();
            $date = $date->format('d.n.o');
            $subject = $attendance->getSubjectId();
            /** @var Subjects $subject */
            if($this->materiiService->getOneBy(['id'=>$subject]))//if the subject exists, it may be deleted
            {
                $subject = $this->materiiService->getOneBy(['id'=>$subject]);
                $subjectName = $subject->getNume();
                array_push($attendanceList, [
                    'subject'=>$subjectName,
                    'class' => $className,
                    'date' => $date,
                    'id' => $attendance->getId()
                ]);
            }
        }
        return $this->response('profesori/attendance-list.html.twig',$this->twig,
        ['list'=>$attendanceList]);
    }

    public function attendanceList($id) :Response
    {
        $nrPresent = 0;
        $allAttendances = $this->studentAttendanceService->getBy(['attendanceId'=>$id]);
        $attendanceTabel = [];
        /** @var StudentAttendance $attendance */
        /** @var ProfessorAttendance $profAttendance */
        $profAttendance = $this->professorAttendanceRepository->findOneBy(['id'=>$id]);
        foreach ($allAttendances as $attendance) {
            $student = $attendance->getStudentId();
            /** @var User $student */
            $student = $this->userService->getOneBy(['id'=>$student]);
            $student = $student->getLastName().' '.$student->getFirstName();
            $distanceStatus = $attendance->getDistanceStatus();
            $distance = $attendance->getDistance();
            $location_exposure = $attendance->getLocationExposure();
            if ($location_exposure === 'ascunsa')
            {
                $location_exposure = null;
            }
            $studTime = $attendance->getCreatedAt();
            //get prof time
            $prof = $profAttendance->getCreatedAt();
            /** @var \DateTimeInterface $prof */
            /** @var \DateInterval $responseTime */
            $responseTime = date_diff($prof, $studTime);
            $hours = $responseTime->h;
            $min = $responseTime->i;
            $seconds = $responseTime->s;

            /** @var StudentAttendance $studAttId */
            $studAttId = $this->studentAttendanceService->getOneBy(['studentId'=>$attendance->getStudentId(), 'attendanceId'=> $id]);
            $studAttId = $studAttId->getId();
            array_push($attendanceTabel,
            [
                'student'=>$student,
                'status' =>$distanceStatus,
                'distance'=>$distance,
                'locationExposure'=>$location_exposure,
                'hours' => $hours,
                'min' => $min,
                'seconds'=>$seconds,
                'id'=>$studAttId
            ]);
            if($distanceStatus == 'Valid')
                $nrPresent ++;
        }
        return $this->response('profesori/attendance-list-by-id.html.twig',$this->twig,
        ['table' => $attendanceTabel,
            'present'=>$nrPresent]);
    }

    public function invalidate($id)
    {
        /** @var StudentAttendance $studAtt */
        $studAtt = $this->studentAttendanceService->repository->findOneBy(['id'=>$id]);
        $attendanceId = $studAtt->getAttendanceId();
        $studAtt->setDistanceStatus('Invalid');
        $this->studentAttendanceService->repository->flush();
        return $this->attendanceList($attendanceId);
    }

    public function validate( $id)
    {
        /** @var StudentAttendance $studAtt */
        $studAtt = $this->studentAttendanceService->repository->findOneBy(['id'=>$id]);
        $attendanceId = $studAtt->getAttendanceId();
        $studAtt->setDistanceStatus('Valid');
        $this->studentAttendanceService->repository->flush();
        return $this->attendanceList($attendanceId);
    }

    public function feedback(User $user, $id, $request)
    {
        //id = studentAttandace
        /** @var StudentAttendance $student */
        $studentAttendance = $this->studentAttendanceService->getOneBy(['id'=>$id]);
        /** @var User $student */
        $student = $this->userService->getOneBy(['id'=>$studentAttendance->getStudentId()]);
        $studentName = $student->getLastName().' '.$student->getFirstName();

        //toate feedback ale lui Salavastru Roxana (la aceasta materie)
        /** @var ProfessorAttendance $profesorAttendance */
        $profesorAttendance = $this->professorAttendanceRepository->findOneBy(['id'=>$studentAttendance->getAttendanceId()]);
        $professorId = $profesorAttendance->getProfessorId();
        $classroomId = $profesorAttendance->getClassroomId();
        $subjectId = $profesorAttendance->getSubjectId();

        //prezentele facute de prof la clasa roxanei
        $profsAttendances = $this->professorAttendanceRepository->findBy(['classroomId' =>$classroomId,'professorId'=>$professorId, 'subjectId'=>$subjectId]);

        $feedbackList = [];
        /** @var ProfessorAttendance $attendance */
        foreach ($profsAttendances as $attendance)
        {
            //prof's attendance id
            $attendanceId = $attendance->getId();

            //looking for this student attendance, it may not exist if the student is not present
            /** @var StudentAttendance $possibleStudentAttendance */
            $possibleStudentAttendance = $this->studentAttendanceService->getOneBy(['attendanceId'=>$attendanceId]);

            /** @var DateTime $classDate */
            $classDate = $attendance->getCreatedAt();
            $classDate = $classDate->format('d.n.o');
            if ($possibleStudentAttendance){

                //feedbach may not exist
                /** @var Feedback $feedback */
                $feedback = $this->feedbackRepo->findOneBy(['studentAttendance'=>$possibleStudentAttendance->getId()]);
                if($feedback)
                {
                    $mark = $feedback->getMark();
                    if(floatval($mark) == 0)
                    {
                        $mark = '';
                    }
                    $remark = $feedback->getRemark();
                    /** @var DateTime $feedbackDate */
                    $feedbackDate = $feedback->getCreatedAt();
                    $feedbackDate = $feedbackDate->format('d.n.o');
                    $bonus = $feedback->getBonus();
                    if(intval($bonus) == 0)
                    {
                        $bonus = '';
                    }

                    array_push($feedbackList,
                        [
                            'feedbackDate' => $feedbackDate,
                            'mark' => $mark,
                            'remark' => $remark,
                            'bonus' => $bonus,
                            'missing' => null,
                            'noFeedback' => null,
                            'classDate' => $classDate
                        ]);
                }
                else
                {
                    //the stud got no feedback for this class
                    array_push($feedbackList,
                        [
                            'feedbackDate' => null,
                            'mark' => null,
                            'remark' => null,
                            'bonus' => null,
                            'missing' => null,
                            'noFeedback' => true,
                            'classDate' => $classDate
                        ]);
                }
            }
            else
            {
                //the stud was absent at this class
                array_push($feedbackList,
                    [
                        'feedbackDate' => null,
                        'mark' => null,
                        'remark' => null,
                        'bonus' => null,
                        'missing' => true,
                        'noFeedback' => null,
                        'classDate' => $classDate
                    ]);
            }
        }

        if ($request->isMethod('POST')) {
            $feedback = new Feedback();
            $mark = $request->get('mark');
            if($mark == null)
                $mark = '';
            $remark = $request->get('remark');
            if($remark == null)
                $remark = '';
            $bonus = $request->get('bonus');
            if($bonus == null)
                $bonus = '';

            $bonus_val = intval($bonus); //int 0 dak profesorul nu a completat, sa nu ii pun 0 studentului
            $mark_val = floatval($mark); // float 0 ca mai sus!

            if ($mark != "" && $mark_val == 0)
            {
                return $this->response('profesori/feedback.html.twig',$this->twig,
                ['mark_error'=>"Nota trebuie sa fie un numar intreg, pozitiv (ex: 3, 2, 5)",
                    'bonus_error' => null,
                    'feedbackList'=>$feedbackList,
                    'studentName' => $studentName]);
            }

            if ($bonus != "" && $bonus_val == 0)
            {
                return $this->response('profesori/feedback.html.twig',$this->twig,
                    ['bonus_error'=>"Nota trebuie sa fie un numar intreg, pozitiv (ex: 3, 2, 5)",
                        'mark_error' => null,
                        'feedbackList'=>$feedbackList,
                        'studentName' => $studentName]);
            }

            $feedback->setCreatedAt();
            $feedback->setStudentAttendance($id);

            $feedback->setBonus($bonus_val);
            if($mark_val != 0)
                $feedback->setMark($mark_val);
            else
                $feedback->setMark('');
            $feedback->setRemark($remark);
            $this->feedbackRepo->persist($feedback);
            $this->feedbackRepo->flush();

            /** @var DateTime $feedbackDate */
            $feedbackDate = $feedback->getCreatedAt();
            $feedbackDate = $feedbackDate->format('d.n.o');

            array_push($feedbackList,
                [
                    'feedbackDate' => $feedbackDate,
                    'mark' => $mark_val,
                    'remark' => $remark,
                    'bonus' => $bonus_val,
                    'missing' => null,
                    'noFeedback' => null,
                    'classDate' => null
                ]);
        }

        return $this->response('profesori/feedback.html.twig',$this->twig,
        ['mark_error'=>null,
            'bonus_error'=>null,
            'feedbackList'=>$feedbackList,
            'studentName' => $studentName]);
    }

}