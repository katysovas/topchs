function responsiveMenu() {
  $('.menuTitle').click(function () {
    $('.menu').slideToggle("slow");
    $('.menu').css({"max-height":"800px"});
  });
};
$(function() {
  $(".menu").css({"display":"none","transition":"none","max-height":"inherit"});
  $("#toggleMenu").remove();
  responsiveMenu();
});
