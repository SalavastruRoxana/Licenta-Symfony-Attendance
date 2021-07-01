window.onload = function () {
    var classes = document.getElementById('clasa');

    if (classes) {
        classes.addEventListener('click', e => {
            if (e.target.className === 'btn btn-danger') {
                if(confirm('Sunteti sigur ca doriti sa stergeti elementul?')){
                    const id = e.target.getAttribute('data-id');
                    fetch(`univ/clasa/${id}`, {
                        method: 'DELETE'
                    }).then(res => window.location.reload());
                }
            }
        });
    }
};