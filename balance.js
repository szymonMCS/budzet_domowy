$(function() {
    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('D/MM/ YYYY') + ' - ' + end.format('D/MM/YYYY'));
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
        "locale": {
            "format": "D MMMM YYYY",
            "separator": " - ",
            "applyLabel": "Zatwierdź",
            "cancelLabel": "Anuluj",
            "fromLabel": "Od",
            "toLabel": "Do",
            "customRangeLabel": "Dowolna",
            "weekLabel": "W",
            "daysOfWeek": [
                "Ni",
                "Po",
                "Wt",
                "Śr",
                "Czw",
                "Pt",
                "So"
            ],
            "monthNames": [
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
            "firstDay": 1
        },
        "alwaysShowCalendars": true,
    }, cb);

    cb(start, end);
});

$(document).ready(function(){
    $(".ranges ul li").click(function(){
        var chosenOption = $(this).text();
        $("#nameOfPeriod").text(chosenOption);
        console.log(this);
        console.log(chosenOption);
    });  
    
    $(".applyBtn.btn.btn-sm.btn-primary").click(function(){
        $("#nameOfPeriod").text("Wybrany okres");
        console.log(this);
    });
});


$(document).ready(function () {

  var name = "";

  $(".ranges ul li").click(function(){
      name = $("#nameOfPeriod").text();
      console.log(name);
      $("#balancePeriod").text(name);
  });

  $(".applyBtn.btn.btn-sm.btn-primary").click(function(){
    name = $("#reportrange span").text();
    console.log(name);
    $("#balancePeriod").text(name);
});
      
});

function calculateBalance(){
    var earnings = parseFloat($("#sumOne").text());
    var expenses = parseFloat($("#sumTwo").text());
    var sum = earnings + expenses;


    if(sum < 0){
        $("#balanceSum").text(sum.toFixed(2));
        $("#balanceSum").css("color", "red");
        $("#balanceDescription").text("Uważaj na wydatki, jesteś na minusie");
    }
    else if(sum === 0){
        $("#balanceSum").text(sum.toFixed(2));
        $("#balanceSum").css("color", "black");
        $("#balanceDescription").text("Jesteś na zero");
    }
    else{
        $("#balanceSum").text("+" + sum.toFixed(2));
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
  


  



