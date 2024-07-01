$(document).ready(function(){
	
	var start;
    var end;
	var savedDate = localStorage.getItem('selectedDate');
	var firstVisit = localStorage.getItem('firstVisit');
	var defaultName = 'Dzisiaj';
	var name = "";
	var dowolnaData = "Wybrany okres";

    if (!firstVisit) {
        start = moment();
        end = moment();
		console.log("1");
        localStorage.setItem('firstVisit', 'true');
        localStorage.setItem('selectedDate', start.format('DD.MM.YYYY') + ' - ' + end.format('DD.MM.YYYY'));
		localStorage.setItem('selectedName', defaultName);
    } else if (savedDate) {
        var dates = savedDate.split(' - ');
        start = moment(dates[0], 'DD.MM.YYYY');
        end = moment(dates[1], 'DD.MM.YYYY');
    } else {
        start = moment();
        end = moment();
    }
	
	var dateValue;
	
	function updateNameOfPeriod(chosenOption) {
        $("#nameOfPeriod").text(chosenOption);
        name = $("#nameOfPeriod").text();
        $("#balancePeriod").text(name);
        localStorage.setItem('selectedName', chosenOption);
    }
	
	function updateSelectedDate() {
        var previousDateValue = localStorage.getItem('selectedDate');
        dateValue = dateValue || $("#reportrange span").text();
        if (dateValue !== previousDateValue) {
            sendDateToServer(dateValue);
        }
    }
	
	function cb(start, end) {
        $('#reportrange span').html(start.format('DD.MM.YYYY') + ' - ' + end.format('DD.MM.YYYY'));
		dateValue = start.format('DD.MM.YYYY') + ' - ' + end.format('DD.MM.YYYY');
		updateSelectedDate();
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Dzisiaj': [moment(), moment()],
            'Wczoraj': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Ostatnie 7 dni': [moment().subtract(6, 'days'), moment()],
            'ostatnie 30 dni': [moment().subtract(29, 'days'), moment()],
            'Ten miesiąc': [moment().startOf('month'), moment().endOf('month')],
            'Poprzedni miesiąc': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            format: "DD.MM.YYYYY",
            separator: " - ",
            applyLabel: "Zatwierdź",
            cancelLabel: "Anuluj",
            fromLabel: "Od",
            toLabel: "Do",
            customRangeLabel: "Dowolna",
            weekLabel: "W",
            daysOfWeek: [
                "Ni",
                "Po",
                "Wt",
                "Śr",
                "Czw",
                "Pt",
                "So"
            ],
            monthNames: [
                "Styczeń",
                "Luty",
                "Marzec",
                "Kwiecień",
                "Maj",
                "Czerwiec",
                "Lipiec",
                "Sierpień",
                "Wrzesień",
                "Październik",
                "November",
                "December"
            ],
            firstDay: 1
        },
        alwaysShowCalendars: true,
    }, cb)	

    cb(start, end);
	
	var savedName = localStorage.getItem('selectedName') || defaultName;
    updateNameOfPeriod(savedName);
	
	function sendDateToServer(dateValue) {
		console.log('Sending date to server: ' + dateValue);
		localStorage.setItem('selectedDate', dateValue);

		$.ajax({
			type: "POST",
			url: "/mojbudzet/balance.php",
			data: { dateValue: dateValue },
			success: function(response) {
				console.log('Odebrany tekst: ' + response);
				location.reload();
			},
			error: function(xhr, status, error) {
				console.error('Error:', xhr.status, status, error);
			}
		});
	}

    $(".ranges ul li").click(function() {
        var chosenOption = $(this).text();
        updateNameOfPeriod(chosenOption);
        setTimeout(updateSelectedDate, 500);
    });

    $(".applyBtn.btn.btn-sm.btn-primary").click(function() {
        name = $("#reportrange span").text();
        updateNameOfPeriod(dowolnaData);
        setTimeout(updateSelectedDate, 500);
    });
	
}); 

function calculateBalance(){
	var earnings = 0;
    var expenses = 0;
	
	if($("#sumOne").text().trim() !== ""){
		earnings = parseFloat($("#sumOne").text());
		if (isNaN(earnings)) {
            earnings = 0;
        }
	}
	
	if ($("#sumTwo").text().trim() !== "") {
        expenses = parseFloat($("#sumTwo").text());
        if (isNaN(expenses)) {
            expenses = 0;
        }
    }
    
    var sum = earnings + expenses;


    if(sum < 0){
        $("#balanceSum").text(sum.toFixed(2) + " zł");
        $("#balanceSum").css("color", "red");
        $("#balanceDescription").text("Uważaj na wydatki, jesteś na minusie");
    }
    else if(sum === 0){
        $("#balanceSum").text(sum.toFixed(2) );
        $("#balanceSum").css("color", "black");
        $("#balanceDescription").text("Jesteś na zero");
    }
    else{
        $("#balanceSum").text("+" + sum.toFixed(2) + " zł");
        $("#balanceSum").css("color", "green");
        $("#balanceDescription").text("Super, zaczynasz oszczędzać");
    }
};


function sliceSize(dataNum, dataTotal) {
    return (dataNum / dataTotal) * 360;
}
  
function addSlice(id, sliceSize, pieElement, offset, sliceID, color) {
	$(pieElement).append(
	  "<div class='slice " + sliceID + "'><span></span></div>"
	);
	var offset = offset - 1;
	var sizeRotation = -179 + sliceSize;

	$(id + " ." + sliceID).css({
	  transform: "rotate(" + offset + "deg) translate3d(0,0,0)"
	});

	$(id + " ." + sliceID + " span").css({
	  transform: "rotate(" + sizeRotation + "deg) translate3d(0,0,0)",
	  "background-color": color
	});
}
  
function iterateSlices(
    id,
    sliceSize,
    pieElement,
    offset,
    dataCount,
    sliceCount,
    color
  ) {
    var maxSize = 179,
      sliceID = "s" + dataCount + "-" + sliceCount;
  
    if (sliceSize <= maxSize) {
      addSlice(id, sliceSize, pieElement, offset, sliceID, color);
    } else {
      addSlice(id, maxSize, pieElement, offset, sliceID, color);
      iterateSlices(
        id,
        sliceSize - maxSize,
        pieElement,
        offset + maxSize,
        dataCount,
        sliceCount + 1,
        color
      );
    }
}
  
function createPie(id) {
	var listData = [],
	  listTotal = 0,
	  offset = 0,
	  i = 0,
	  pieElement = id + " .pie-chart__pie";
	dataElement = id + " .pie-chart__legend";

	color = [
	  "cornflowerblue",
	  "olivedrab",
	  "orange",
	  "tomato",
	  "crimson",
	  "purple",
	  "turquoise",
	  "forestgreen",
	  "navy"
	];
  
    color = shuffle(color);
  
    $(dataElement + " span").each(function () {
      listData.push(Number($(this).html()));
    });
  
    for (i = 0; i < listData.length; i++) {
      listTotal += listData[i];
    }
  
    for (i = 0; i < listData.length; i++) {
      var size = sliceSize(listData[i], listTotal);
      iterateSlices(id, size, pieElement, offset, i, 0, color[i]);
      $(dataElement + " li:nth-child(" + (i + 1) + ")").css(
        "border-color",
        color[i]
      );
      offset += size;
    }
}
  
function shuffle(a) {
    var j, x, i;
    for (i = a.length; i; i--) {
      j = Math.floor(Math.random() * i);
      x = a[i - 1];
      a[i - 1] = a[j];
      a[j] = x;
    }
  
    return a;
}
  
function createPieCharts() {
    createPie(".pieID--przychody");
    createPie(".pieID--wydatki");
}
  
createPieCharts();

calculateBalance();



  



