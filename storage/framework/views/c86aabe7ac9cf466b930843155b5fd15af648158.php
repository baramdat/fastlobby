

<?php $__env->startSection('title'); ?>
Video chat
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3"> </div>
        <div class="col-lg-6">
            <h3 class="text-center mt-3">Select Users For Chat</h3>
            <div class="card">
                <ul class="list-group list-group-flush">
                    <?php if(isset($users) && sizeof($users) > 0): ?>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    if ($user->image == NULL) {
                        $img = "assets/images/users/avatar.jpg";
                    } else {
                        $img = '/uploads/files/' . $user->image;
                    }
                    ?>
                    <div class="row p-2">
                        <div class="col-lg-8">
                            <li class="list-group-item"><span><img src="<?php echo e(asset($img)); ?>" id="user_two" style="width:40px;height:40px;border-radius:50%;"></span> <?php echo e(ucwords($user->first_name)); ?> <?php echo e(ucwords($user->last_name)); ?> ( <?php echo e($user->roles->pluck('name')->first()); ?> )</li>
                        </div>
                        <div class="col-lg-4 text-center pt-5"><button class="btn btn-primary btn-sm" data-id="<?php echo e($user->id); ?>" id="btnSubmit"><i class="fa fa-spinner fa-pulse" style="display: none;"></i> Start Chat</button></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="col-lg-3">
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('bottom-script'); ?>
<script>
    $(document).ready(function() {
        $('#roomName').attr('required');
        $(document).on('click', '#btnSubmit', function() {
            var id = $(this).attr('data-id');
            console.log(id);
            $.ajax({
                type: "POST",
                url: "/api/create/specific/room/" + id,
                data: {
                    id: id
                },
                dataType: "JSON",
                cache: false,
                success: function(response) {
                    if (response["status"] == "fail") {
                        alert(response["msg"]);
                    } else if (response["status"] == "success") {
                        window.location.href = response["url"];
                    }
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lok3rn5/fastlobby.com/resources/views/templates/chat/compose_video_chat.blade.php ENDPATH**/ ?>