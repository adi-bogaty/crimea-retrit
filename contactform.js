$(document).ready(function() {
  $(".ajax-contact-form").submit(function() {
    var str = $(this).serialize();

    $.ajax({
      type: "POST",
      url: "contact.php",
      data: str,
      success: function(msg) {
        if(msg == 'OK') {
          result = '<p>Благодарим! Ваш заявка принята.</p><p>Мы с вами свяжемся.</p>';
          $(".fields").hide();
        } else {
        result = msg;
        }
        $('.note').html(result);
      }
    });
    return false;
  });
});
