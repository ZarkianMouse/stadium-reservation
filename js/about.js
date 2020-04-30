// Used from tutorial on W3Schools
// https://www.w3schools.com/howto/howto_js_tabs.asp

var tabButtons = document.querySelectorAll(".tabContainer .buttonContainer button");
var tabPanels = document.querySelectorAll(".tabContainer .tabPanel");

function showPanel(panelIndex,colorCode) {
	tabButtons.forEach(function(node){
		node.style.backgroundColor="";
		node.style.color="";
	});
	tabButtons[panelIndex].style.backgroundColor="lightyellow";
	tabButtons[panelIndex].style.color="black";
	tabPanels.forEach(function(node) {
		node.style.display="none";
	});

	tabPanels[panelIndex].style.display="block";
	tabPanels[panelIndex].style.backgroundColor="lightyellow";

}
showPanel(0,'prices');