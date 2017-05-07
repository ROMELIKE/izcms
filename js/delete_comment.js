$(document).ready(function(){
	$('.remove').click(function(){
		var cmid=$('.remove').attr('id');
		var container=$(this).parent();
		// alert(cmid);
		$.ajax({
			type:"POST",
			url:"includes/delete_comment.php",
			data:"cmt_id="+cmid,
			success:function(){
				container.slideUp('slow',function(){
					container.remove();
				});
			}
		});
	});
});