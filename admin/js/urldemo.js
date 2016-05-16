$(function() {

	$('#shorten-url').click(function(evt) {
		evt.preventDefault();
		if($('#long-url').length === 0 || $('#long-url').val() === '') {
			alert('You need to enter a URL to shorten.');
		} else {
			$.ajax({
				type: 'POST',
				url: 'php/curl.php',
				data: 'longUrl=' + $('#long-url').val(),
				success: function(data) {
					$('#long-url').val(data);
				}
			});
		} // end if/else
	});
	
	$('#expand-url').click(function(evt) {
		evt.preventDefault();
		if($('#short-url').length === 0 || $('#short-url').val() === '') {
			alert('You need to enter a URL to expand.');
		} else {
			$.ajax({
				type: 'GET',
				url: 'php/curl.php',
				data: 'shortUrl=' + $('#short-url').val(),
				success: function(data) {
					$('#lengthened-url').html(data);
				}
			});
		} // end if/else
	});
	
});

function countChar(val) {
    var len = val.value.length;
    if (len >= 160) {
      val.value = val.value.substring(0, 160);
    } else {
      $('#charNum').text(160 - len);
    }
  };