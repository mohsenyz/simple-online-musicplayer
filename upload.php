<!DOCTYPE html>
<html>
<head>
	<title>Uploader</title>
	<style type="text/css">
		*{
			transition: 0.3s;
		}
		body{
			background: #eaeaea;
			text-align: center;
		}
		#container{
			background: white;
			box-shadow: 0px 0px 15px black;
			border-radius: 0px 0px 4px 4px;
			border-top: 3px purple solid;
			width: 70%;
			margin: 50px auto;
			padding: 20px;
			text-align: center;
			position: relative;
			z-index: 2;
		}
		.upload-btn{
			cursor: pointer;
			padding: 15px 20px;
			background: #297cde;
			color: white;
			border-radius: 4px;
			box-shadow: 0px 0px 5px black;
			transition: 0.3s;
			position: relative;
			z-index: 5;
		}
		.upload-btn:hover{
			background: blue;
		}
		input#file{
			opacity: 0;
			position: absolute;
			z-index: -1;
		}
		#upload{
			cursor: pointer;
			padding: 15px 20px;
			background: purple;
			color: white;
			border-radius: 0px 0px 4px 4px;
			box-shadow: 0px 0px 5px black;
			transition: 0.3s;
			border: 0;
			position: relative;
			top: -50px;
			z-index: 1;
		}
		#upload:hover{
			box-shadow: 0px 0px 15px black;
		}
		#progress{
			position: absolute;
			z-index: 3;
			top: 0px;
			left: 0px;
			width: 70%;
			background: orange;
			opacity: 0.8;
			height: 100%;
			border-right: 3px red solid;
			display: none;
		}
		#progress > div{
			position: relative;
			width:100%;
			height: 100%;
		}
		#progress > div > span{
			position: absolute;
			bottom: 100%;
			right: -21px;
			width: 40px;
			height: 20px;
			text-align: center;
			vertical-align: middle;
			background: red;
			font-size: small;
			font-weight: bold;
			padding-top: 5px;
			border-radius: 7px 7px 0px 0px;
		}
		#progress > div > span:hover{
			box-shadow: 0px 0px 5px black;
		}
	</style>
</head>
<body>
	<div id="container">
		<div id="progress"><div><span id="progress-text">50%</span></div></div>
		<label for="file" class="upload-btn" id="upload-btn-main">Select file</label>
		<input type="file" name="file" id="file">
	</div>

	<button id="upload" onclick="uploadFile();">Upload!</button>
</body>
<input type="hidden" name="hi" id="hi-him">
<script type="text/javascript">
	var url = "/uploadfile.php";
	function $(el){
		return document.getElementById(el);
	}
	$("file").onchange = function(e){
		$("upload-btn-main").innerHTML = "Selected file " + $("file").files[0].name + " (size :"+ getSize($("file").files[0].size) + " )";
	}
	function getSize(size){
		if (size < 1024 * 1024){
			return Math.round(size / 1024) + " kb";
		}else{
			return Math.round(size / 1024 / 1024) + " mb";
		}
	}
	var ajax;
	function uploadFile(){
		if ($("file").files.length == 0){
			alert('please select a file');
			return;
		}
		if (ajax != null){
			ajax.abort();
			ajax = null;
			uploadFinished();
			return;
		}
		var formData = new FormData();
		formData.append("image", $("file").files[0]);
		ajax = new XMLHttpRequest();
		ajax.onreadystatechange = function(){
			if (ajax.readyState == 1) {
				$("upload").innerHTML = "Cancel Upload";
			}
			if (ajax.readyState == 4) {
				if (ajax.status == 200){
					uploadCompleted();
				}else{
					alert('Error: ' + ajax.statusText);
				}
				uploadFinished();
			}
		};
		ajax.upload.addEventListener('progress', function(e){
        	window.setTimeout(function(){
        		var pre = Math.ceil(e.loaded/e.total * 100);
        		$("progress").style.width = pre + '%';
        		$("progress-text").innerHTML = pre + "%";
        	}, 0);
    	}, false);
    	ajax.open('POST', url, true);
    	ajax.send(formData);
    	initalizeProgress();
	}
	function uploadFinished(){
		$("progress").style.display = "none";
		$("upload").innerHTML = "Upload!";
		$("file").value = "";
		$("upload-btn-main").innerHTML = "Select file";
		ajax = null;
	}
	function uploadCompleted(){
		alert('Uploaded!');
        window.location = "/";
	}
	function initalizeProgress(){
		$("progress").style.display = "block";
		$("progress-text").innerHTML = "0%";
		$("progress").style.width = 0 + '%';
	}
</script>
</html>
