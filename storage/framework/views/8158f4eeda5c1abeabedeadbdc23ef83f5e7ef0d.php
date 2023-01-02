

<?php $__env->startSection('title'); ?>
Camera list
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php
use \App\Http\Controllers\PickupController;
?>
<style>

</style>

<!-- CONTAINER -->
<div class="main-container container-fluid">


    <!-- PAGE-HEADER Breadcrumbs-->
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW-1 -->
    <div class="row">
        <div class="col-12 mb-3">
            Hello <?php echo e(Auth::user()->buisness_name); ?> | <small class="badge bg-success"><?php echo e(ucwords(Auth::user()->roles->pluck('name')[0])); ?></small>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 ">
            <div class="card border p-0 overflow-hidden shadow-none">
                <div class="card-header ">
                    <div class="media mt-0">
                        <div class="media-body">
                            <h4 class="mb-0 mt-1">Camera's List</h4>
                            <h6 class="mb-0 mt-3 text-muted">List of camera with their streaming .</h6>
                        </div>
                    </div>
                </div>

                <!-- <div class="card-body py-3 px-4" style="max-height: 295px; overflow-y: scroll;   scrollbar-width: none;">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table  text-nowrap text-sm-nowrap table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Channel</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="channelList">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="divLoader" class="text-center pt-5" style="height:300px;">
                            <span>
                                <i class="fe fe-spinner fa-spin"></i> Cameras are being loading.. It
                                might take few
                                seconds.
                            </span>
                        </div>
                    </div>

                </div> -->

                <div class="container">
                    <div class="row chat-row">
                        <div class="row justify-content-center align-items-center g-2" id="channelList">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>






    </div>
    <!-- ROW-1 END -->

</div>
<!-- CONTAINER END -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.socket.io/4.0.1/socket.io.min.js" integrity="sha384-LzhRnpGmQP+lOvWruF/lgkcqD+WDVt9fU3H4BWmwP5u5LTmkUGafMcpZKNObVMLU" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
<script>
    $(document).ready(function() {
        // $(function() {
        //     setInterval(sitesList, 10000);
        // });
         sitesList();
        function sitesList() {
            $.ajax({
                url: '/api/CameraList',
                type: "post",
                dataType: "JSON",
                cache: false,
                beforeSend: function() {},
                complete: function() {},
                success: function(response) {
                    // console.log(response)
                    if (response["status"] == "success") {
                        $("#channelList").html(response['data']);
                        $("#divLoader").css('display', 'none');
                    } else if (response["status"] == "fail") {

                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
        
        $(document).ready(function () {
        showSpinnerWhileIFrameLoads();
        });

        function showSpinnerWhileIFrameLoads() {
            var iframe = $('iframe');
        if (iframe.length) {
            $(iframe).before('<div class=\'spinner\'><i class=\'fa fa-spinner fa-spin fa-3x fa-fw\'></i></div>');
            $(iframe).on('load', function() {
            document.getElementByClass('spinner').style.display='none';
        });
    }
}

    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lok3rn5/fastlobby.com/resources/views/templates/tenant/camera/camera_list.blade.php ENDPATH**/ ?>