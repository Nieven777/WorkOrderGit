<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Work Order Monitoring</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">
    
    <!-- ✅ CSS Files (Load First) -->
    <!-- <link href="{{ asset('/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"> -->

    <!-- ✅ jQuery (Load First for Dependencies) -->
    <!-- <script src="{{ asset('/js/jquery-3.5.1.min.js') }}"></script>    
    <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/js/dataTables.bootstrap4.min.js') }}"></script> -->

    <!-- ✅ Font Awesome & Feather Icons (Defer Loading) -->
    <!-- <script defer src="{{ asset('/js/all.min.js') }}"></script>
    <script defer src="{{ asset('/js/feather.min.js') }}"></script> -->
    
    <!-- <script src="{{ asset('/js/scripts.js') }}"></script> -->

    <!-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script> -->


    <!-- ✅ Vue & Inertia.js -->
    @vite(['resources/js/app.js']) 

    

</head>
<body>
    @inertia

    <!-- <script src="{{ asset('assets/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('assets/demo/chart-pie-demo.js') }}"></script>
    <script src="{{ asset('assets/demo/datatables-demo.js') }}"></script> -->

</body>
</html>
