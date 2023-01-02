<?php
    $user = Auth::user();
    $chatCount = App\Models\Conversation::where('receiver_id',$user->id)->orWhere('sender_id',$user->id)->where('is_sender','yes')->count();

?>
<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
    <div class="card">
        <div class="list-group list-group-transparent mb-0 mail-inbox pb-3">
            <div class="mt-4 mb-4 mx-4 text-center">
                <a href="/message/compose" class="btn btn-primary btn-lg d-grid">Compose</a>
            </div>
            <a href="/message/inbox" class="list-group-item d-flex align-items-center active mx-4">
                <span class="icons"><i class="ri-mail-line"></i></span> Inbox <span class="ms-auto badge bg-secondary bradius"><?php echo e($chatCount); ?></span>
            </a>
            <a href="/message/sent" class="list-group-item d-flex align-items-center mx-4">
                <span class="icons"><i class="ri-mail-send-line"></i></span> Sent Mail
            </a>

        </div>

    </div>
</div><?php /**PATH /home/lok3rn5/fastlobby.com/resources/views/templates/chat/sidebar.blade.php ENDPATH**/ ?>