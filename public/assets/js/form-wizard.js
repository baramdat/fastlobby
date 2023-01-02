(function($) {
    "use strict";
    $('#wizard1').steps({
        headerTag: 'h3',
        bodyTag: 'section',
        autoFocus: true,
        titleTemplate: '<span class="number">#index#<\/span> <span class="title">#title#<\/span>',
        onFinished: function(event,currentIndex){
            $("#add_transaction").submit();

        }
    });
    
    
    $('#wizard3').steps({
        headerTag: 'h3',
        bodyTag: 'section',
        autoFocus: true,
        titleTemplate: '<span class="number">#index#<\/span> <span class="title">#title#<\/span>',
        stepsOrientation: 1
    });

    $('.dropify-clear').click(function() {
        $('.dropify-render img').remove();
        $(".dropify-preview").css("display", "none");
        $(".dropify-clear").css("display", "none");
    });

    //_________accordion-wizard
    var options = {
        mode: 'wizard',
        autoButtonsNextClass: 'btn btn-primary float-end',
        autoButtonsPrevClass: 'btn btn-light',
        stepNumberClass: 'badge rounded-pill bg-primary me-1',
        onSubmit: function() {
            alert('Form submitted!');
            return true;
        }
    }
    $("#form").accWizard(options);

})(jQuery);

//Function to show image before upload

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.dropify-render img').remove();
            var img = $('<img id="dropify-img">'); //Equivalent: $(document.createElement('img'))
            img.attr('src', e.target.result);
            img.appendTo('.dropify-render');
            $(".dropify-preview").css("display", "block");
            $(".dropify-clear").css("display", "block");
        };
        reader.readAsDataURL(input.files[0]);
    }
}