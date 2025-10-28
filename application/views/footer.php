</td>
</tr>
<tr>
	<td colspan="2">
		<div class="footer">
			Samick Production Management System Guitar (SPMS-G)<br>
			PT. Samick Indonesia &copy; 2009
		</div>
	</td>
</tr>
</table>
</div>
<script>
	function addComboBoxListeners(
		inputShow,
		input,
		dropdown,
		searchBox,
		container,
		act
	) {
		function scrollToSelectedOption() {
			var selectedOption = dropdown.querySelector("li.selected");
			if (selectedOption) {
				var selectedOptionRect = selectedOption.getBoundingClientRect();
				var dropdownRect = dropdown.getBoundingClientRect();
				if (selectedOptionRect.bottom > dropdownRect.bottom || selectedOptionRect.top < dropdownRect.top) {
					dropdown.scrollTop = selectedOption.offsetTop;
				}
			}
		}

		var initialSelectedOptions = dropdown.querySelectorAll("li.selected");
		if (initialSelectedOptions.length > 0) {
			initialSelectedOptions.forEach(function(option) {
				input.value = option.getAttribute("data-value");
				inputShow.value = option.textContent;
			});
		}
		var dropdownItems = dropdown.querySelectorAll("li");
		dropdownItems.forEach(function(item) {
			item.addEventListener("click", function() {
				var getContent = item.textContent;
				var getCode = item.getAttribute("data-value");
				if (!item.classList.contains("selected")) {
					dropdownItems.forEach(function(otherItem) {
						otherItem.classList.remove("selected");
					});
					item.classList.add("selected");
					input.value = getCode;
					inputShow.value = getContent;
					if (act == 'getColor') {
						getColor(getCode);
					}
				} else {
					item.classList.remove("selected");
					input.value = "";
					inputShow.value = "";
					if (act == 'getColor') {
						getColor();
					}
				}
				dropdown.style.display = "none";
				searchBox.style.display = "none";
				scrollToSelectedOption();
			});
		});
		searchBox.addEventListener("input", function() {
			var searchTerm = searchBox.value.trim().toLowerCase();
			Array.from(dropdown.children).forEach(function(option) {
				var optionText = option.textContent.toLowerCase();
				option.style.display = optionText.includes(searchTerm) ?
					"block" :
					"none";
			});
			scrollToSelectedOption();
		});
		document.addEventListener("click", function(event) {
			var clickedElement = event.target;
			if (
				!dropdown.contains(clickedElement) &&
				!searchBox.contains(clickedElement) &&
				clickedElement !== inputShow
			) {
				dropdown.style.display = "none";
				searchBox.style.display = "none";
			}
		});
		inputShow.addEventListener("click", function() {
			if (dropdown.style.display === "none") {
				dropdown.style.display = "block";
				searchBox.style.display = "block";
				searchBox.focus();
				dropdown.style.width = container.offsetWidth + "px";
				scrollToSelectedOption();
			} else {
				dropdown.style.display = "none";
				searchBox.style.display = "none";
			}
		});
		window.addEventListener("scroll", function() {
			dropdown.style.display = "none";
			searchBox.style.display = "none";
		});
		inputShow.click();
	}
</script>

</body>

</html>