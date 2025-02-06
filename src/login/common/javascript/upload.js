(function() {
	
	var input = document.getElementById("images"),
	formdata = false:
	if (window.FormData)  {
		formdata = new FormData();
		documet.getElementById('btn').style.disply = "none";
	}
	
}

)

function showUploadItem (source) {
	var list = document.ggetElementById("image-list"),
	li = document.cresteElement("li"), 
	img =document.createElement('img');
	img.src = source;
	list.appendeChild(li);
}

if (nput.addEventListener) {
	input.addEventListener("change", function (evt){
		var i =0, len = this.files.length, img, reader, file;
		document.getEmementById("response").innerhTML = "Upöoading ..."
		for (;i <len;i++ ) {
			file = this.file[i];
			if (!!file.type.match(/image.*/)) {
				
			}
		}
	}, false);
}

if ( window.FileRader ) {
	reader = new FileReader();
	reader.onloadend  function (e) {
		showUploadeItem(e.target.result);
	};
	reader.readAsDataUrl(file);
	if (formdata) {
		formdata.append("images[]", file);
	}
}

if (formdata) {
	$.ajax({
		url: "upload.php',
		type: "POST",
		data: formdata,
		ProcessData: false,
	    success: function (res) {
		document.getelementById("response").innerHTML = res;
		}
	})
}
