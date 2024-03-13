//grid generate
if ($(".text").hasClass("schema6")) {
    $(".content").addClass("grid");
}

//arrow in menu
$(".nav li").has("ul").addClass("triangle");

//contact form
$('#form').submit(function (e) {
    e.preventDefault();

    $('.error').html('');
    var form = $(this);
    var name = $('#name').val();
    var surname = $('#surname').val();
    var hasError = false;

    if (name.trim() === '') {
        $('#nameError').html('Please enter your name');
        hasError = true;
    }

    if (surname.trim() === '') {
        $('#surnameError').html('Please enter your surname');
        hasError = true;
    }

    if (hasError) {
        return false;
    }

    $.ajax({
        type: 'POST',
        url: 'send',
        data: form.serialize(),
        success: function () {
            $('#contact_form').html("Success");
        }

    });
});

