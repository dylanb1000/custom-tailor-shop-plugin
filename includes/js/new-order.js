function update_price() {
	let subtotal = 0;
	let prices = document.getElementsByClassName("price-data");
	for (let i = 0; i < prices.length; i++) {
		subtotal += parseFloat(prices.item(i).value);
	}
	subtotal = subtotal.toFixed(2);
	let taxes = (subtotal * 0.085).toFixed(2);
	let total = (parseFloat(subtotal) + parseFloat(taxes)).toFixed(2);

	const subtotal_text = document.getElementById("subtotal");
	subtotal_text.innerHTML = " $" + subtotal;
	const taxes_text = document.getElementById("taxes");
	taxes_text.innerHTML = " $" + taxes;
	const total_text = document.getElementById("total");
	total_text.innerHTML = " $" + total;

	window.requestAnimationFrame(update_price);
}
update_price();
document.getElementById("current_date").innerHTML = "Date/Time: " + Date();
var currentDate = new Date();
document.getElementById("invoice").value = Date.parse(currentDate) / 1000;
var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
	showSlides(slideIndex += n);
}

function currentSlide(n) {
	showSlides(slideIndex = n);
}

function showSlides(n) {
	var i;
	var slides = document.getElementsByClassName("mySlides");
	var dots = document.getElementsByClassName("dot");
	if (n > slides.length) {
		slideIndex = 1
	}
	if (n < 1) {
		slideIndex = slides.length
	}
	for (i = 0; i < slides.length; i++) {
		slides[i].style.display = "none";
	}
	for (i = 0; i < dots.length; i++) {
		dots[i].className = dots[i].className.replace(" active", "");
	}
	slides[slideIndex - 1].style.display = "block";
	dots[slideIndex - 1].className += " active";
}
var count = 2;

function add_individual_form() {
	var form = document.getElementById("replicate");
	var clone = form.cloneNode(true);
	clone.removeChild(clone.lastElementChild);
	var main = clone.querySelector("#individual_data").querySelector("#individual_data");
	main.querySelector("#description-1").id = "description-" + count;
	main.querySelector("#price-1").id = "price-" + count;
	clone.querySelector("#individual_data").querySelector("#notes-box").querySelector("#notes").value="";
	clone.querySelector("#individual_data").querySelector("#photo-box").querySelector("#photo").value = "";
	clone.id = "";
	clone.style = "display: none;";
	var beforeNode = document.getElementById("insertBefore");
	document.getElementById("slideshow-container").insertBefore(clone, beforeNode);

	//add dynamic function
	var scr = document.createElement("script");
	scr.innerHTML = "var selector = document.getElementById(`description-" + count + "`);document.getElementById(`price-" + count + "`).value=selector.options[selector.selectedIndex].value;selector.addEventListener(`change`,function handleChange(event){document.getElementById(`price-" + count + "`).value=event.target.value;});"
	clone.appendChild(scr);
	count++;

	//add dot element
	var dot = document.createElement("span");
	dot.classList.add("dot");
	var current_dot_length = document.getElementById("dot-container").children.length + 1;
	dot.setAttribute("onclick", "currentSlide(" + current_dot_length + ")");
	document.getElementById("dot-container").appendChild(dot);

}


function generate_submit_form_data() {
	dataForm = document.createElement("form");
	dataForm.action = "/new-order-form-processing/";
	dataForm.method = "post";
	dataForm.id = "posting_form";
	dataForm.enctype = "multipart/form-data"

	//hidden fields for global form variables
	//name
	var name = document.createElement("input");
	name.type = "hidden";
	name.name = "form_name";
	name.value = document.getElementById("name").value;
	dataForm.appendChild(name);
	//email
	var email = document.createElement("input");
	email.type = "hidden";
	email.name = "email";
	email.value = document.getElementById("email").value;
	dataForm.appendChild(email);
	//phone
	var phone = document.createElement("input");
	phone.type = "hidden";
	phone.name = "phone";
	phone.value = document.getElementById("phone").value;
	dataForm.appendChild(phone);
	//estimate
	var estimate = document.createElement("input");
	estimate.type = "hidden";
	estimate.name = "estimate";
	estimate.value = document.getElementById("estimate").value;
	dataForm.appendChild(estimate);
	//invoice
	var invoice = document.createElement("input");
	invoice.type = "hidden";
	invoice.name = "invoice";
	invoice.value = document.getElementById("invoice").value;
	dataForm.appendChild(invoice);
	//subtotal
	var subtotal = document.createElement("input");
	subtotal.type = "hidden";
	subtotal.name = "subtotal";
	subtotal.value = document.getElementById("subtotal").innerHTML;
	dataForm.appendChild(subtotal);
	//taxes
	var taxes = document.createElement("input");
	taxes.type = "hidden";
	taxes.name = "taxes";
	taxes.value = document.getElementById("taxes").innerHTML;
	dataForm.appendChild(taxes);
	//total
	var total = document.createElement("input");
	total.type = "hidden";
	total.name = "total";
	total.value = document.getElementById("total").innerHTML;
	dataForm.appendChild(total);
	//tender
	var tender = document.createElement("input");
	tender.type = "hidden";
	tender.name = "tender";
	tender.value = document.getElementById("tender").options[document.getElementById("tender").selectedIndex].innerHTML;
	dataForm.appendChild(tender);

	//Add form data
	var garment = document.getElementsByClassName("garment-select");
	var description = document.getElementsByClassName("description-select");
	var price = document.getElementsByClassName("price-data");
	var photo = document.getElementsByClassName("photo-file");
	var notes = document.getElementsByClassName("notes");
	var hidden_garment = [];
	var hidden_description = [];
	var hidden_price = [];
	var hidden_photo = [];
	var hidden_notes=[];
	for (var i = 0; i < garment.length; i++) {
		hidden_garment[i] = document.createElement("input");
		hidden_garment[i].type = "hidden";
		hidden_garment[i].name = "garment-" + i;
		hidden_garment[i].value = garment.item(i).value;
		dataForm.appendChild(hidden_garment[i]);

		hidden_description[i] = document.createElement("input");
		hidden_description[i].type = "hidden";
		hidden_description[i].name = "description-" + i;
		hidden_description[i].value = description.item(i).options[description.item(i).selectedIndex].innerHTML;
		dataForm.appendChild(hidden_description[i]);

		hidden_price[i] = document.createElement("input");
		hidden_price[i].type = "hidden";
		hidden_price[i].name = "price-" + i;
		hidden_price[i].value = price.item(i).value;
		dataForm.appendChild(hidden_price[i]);

		hidden_photo[i] = photo.item(i).cloneNode(true);
		hidden_photo[i].name = "photo-" + i;
		dataForm.appendChild(hidden_photo[i]);

		hidden_notes[i]=document.createElement("input");
        hidden_notes[i].type="hidden";
        hidden_notes[i].name="notes-"+i;
        hidden_notes[i].value=notes.item(i).value;
        dataForm.appendChild(hidden_notes[i]);
	}

	//add form to html/simple field check

	document.getElementById("submit").append(dataForm);

	var valueElement = 0;
	var posting_form = document.getElementById("posting_form");
	for (var i = 0; i < posting_form.length; ++i) {
		var inputNode = posting_form[i];
		if (inputNode.value != "") {
			valueElement += 1;
		}
	}
	/*if (valueElement != (9 + (5 * (garment.length)))) {
		dataForm.remove();
		alert("Fill out all Fields!");
	} else {
		//submit form
	}*/
		dataForm.submit();
	
}