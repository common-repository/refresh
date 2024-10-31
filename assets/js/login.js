window.onload = function() {

  // Insert illustration
  var illu = document.createElement('div');
  illu.setAttribute('class', 'flex-box login-illustration');

  var custom_login_img = document.getElementById('custom_login');
  var custom_login_bg  = document.getElementById('custom_login_bg');

  if(custom_login_img) {
    illu.innerHTML = '<img alt="" src="'+custom_login_img.dataset.img+'" style="max-width: 200px; width:100%; position: absolute; left: 50px; top: 50px;"><img src="'+custom_login_bg.dataset.img+'" alt="">';
  } else {
    illu.innerHTML = '<img src="'+custom_login_bg.dataset.img+'" alt="">';
  }


  document.body.insertBefore(illu, document.body.firstChild);

  // Label to placeholders

  var labels = document.querySelectorAll("label");
  var i = labels.length;

  while (i--) {
    var label = labels.item(i);
    var text = label.textContent;
    if ( label.getElementsByTagName('input')[0] != null ) {
      label.getElementsByTagName('input')[0].setAttribute("placeholder", text);
    }
  }

};
