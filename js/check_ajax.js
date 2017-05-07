$(document).ready(function(){
	/*dùng ajax kiểm tra tính độc nhất của email*/
	$('#email').change(function(){
		var email=$(this).val();
		if(email.length>8){
			$('#available').html('<span class="success">Email chuẩn</span>');
			$.ajax({
				type:"get",
				url:"check.php",
				data:"email="+email,
				success:function(response){
					if(response== "YES"){
						$('#available').html('<span class="success">email có thể sử dụng</span>');
					}else if(response == "NO"){
						$('#available').html('<span class="warning">email không thể sử dụng</span>');
					}
				}

			})
		}else{
			$('#available').html('<span class="warning">Email quá ngắn</span>');
		}
	});
	/*kiểm tra 2 pass nhập vào có khớp nhau hay không*/
	$('#pass2').change(function(){
		var p1=$('#pass1').val();
		var p2=$(this).val();
		console.log(p1);
		console.log(p2);
		if(p1==p2){
			$('#pass-errors').html('<span class="success">pass khớp</span>');
			}else{
			$('#pass-errors').html('<span class="warning">pass không khớp</span>');
		}
	});
});

