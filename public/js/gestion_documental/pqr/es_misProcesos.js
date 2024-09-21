Echo.private('App.misPQR.{{Auth::user()->id}}')
    .listen('App\\Events\\FuncionarioPQR', (data) => {
        misCoEx();
        misCoIn();
        misCoSa();
    });