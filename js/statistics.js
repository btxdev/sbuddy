var scrollPage = 0;
var timePage = 0;
var timepagea = true; //по умолчанию вкладка активна

window.addEventListener('beforeunload', scrollPageFunc);
window.onfocus = function(){ timepagea = true; } //пользователь на вкладке сайте
window.onblur = function(){ timepagea = false; } //пользователь закрыл вкладку или переключил на другую

$(document).ready(function(){
  scrollPage = Number(scrollCheck());
  $(window).scroll(function(){
    scrollPage = Number(scrollCheck());
  })
  // statistics init
  Statistics.init();
  if(window.requestIdleCallback) {
    Statistics.initIdleId = requestIdleCallback(Statistics.init);
  }
  else {
    Statistics.initIdleId = setTimeout(Statistics.init, 500);
  }
});


setInterval(function(){
  if(!timepagea) return;
  timePage++;
}, 1000)


function scrollPageFunc(){
  Statistics.send();
}

function scrollCheck(){
  var heightContentPage = $('body').height();
  var heightScrollPage = $(window).scrollTop() + document.documentElement.clientHeight;
  var heightScrollPageP = (heightScrollPage * 100 / heightContentPage).toFixed(2);
  if(heightScrollPageP > 100){
    heightScrollPageP = (100).toFixed(2);
  }
  if(scrollPage < heightScrollPageP){
    return heightScrollPageP;
  } else{
    return scrollPage;
  }
}

var Statistics = {
  evercookie: undefined,
  userid: undefined,
  called: false,
  ready: false,
  init: function() {
    if(Statistics.called === true) return;
    Statistics.called = true;
    Statistics.evercookie = new evercookie();
    Fingerprint2.get(function(components) {
      Statistics.userid = Fingerprint2.x64hash128(components.map(function (pair) { return pair.value }).join(), 31);
      Statistics.evercookie.get('_stat_userid', function(best, all) {
        if((typeof(best) == 'null') || (best == 'null')) {
          var hash = Statistics.userid;
          if((typeof(hash) != 'string') || (hash == 'null') || (hash == '')) {
            hash = 'none';
          }
          Statistics.evercookie.set('_stat_userid', hash);
        }
        else {
          Statistics.userid = best;
        }
        Statistics.ready = true;
      });
    });
  },
  send: function() {
    // data
    var url = 'php/db_stat.php';
    var data = {
      reg_view: true,
      key: Statistics.userid,
      page: currentFileName,
      percent: scrollPage,
      time: timePage
    };
    // request
    if(navigator.sendBeacon) {
      var formData = new FormData();
      for(key in data) {
        formData.append(key, data[key]);
      }
      navigator.sendBeacon(url, formData);
    }
    else {
      $.ajax({
        type: 'POST',
        url: url,
        data: data,
        success: function(response) { }
      });
    }
  }
};
