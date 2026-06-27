(function ($) {
  var c = window.RadioPanelLogin || {};
  var $form = $('#login-form');
  var $submit = $('#login-submit');
  var $submitText = $submit.find('.login-submit__text');
  var $submitLoader = $submit.find('.login-submit__loader');
  var $alert = $('#login-alert');
  var $avatar = $('#user-avatar');
  var $success = $('#login-success');
  var $username = $('#username');
  var $password = $('#password');
  var searchTimer = null;

  function cap(s) {
    return s ? s.charAt(0).toUpperCase() + s.slice(1) : '';
  }

  function alertMsg(msg, show) {
    $alert.text(show ? msg : '').prop('hidden', !show);
  }

  function fieldState($input, $status, ok) {
    $input.removeClass('is-valid is-invalid');
    $status.removeClass('is-valid is-invalid').empty();
    if (ok === true) {
      $input.addClass('is-valid');
      $status.addClass('is-valid').html('<i class="fa-solid fa-check"></i>');
    } else if (ok === false) {
      $input.addClass('is-invalid');
      $status.addClass('is-invalid').html('<i class="fa-solid fa-xmark"></i>');
    }
  }

  function loading(on) {
    $submit.prop('disabled', on);
    $submitText.prop('hidden', on);
    $submitLoader.prop('hidden', !on);
  }

  function setAvatar(data) {
    var fb = c.defaultAvatar || '';
    if (!data || !data.img) {
      $avatar.attr('src', fb).css({ borderColor: '', boxShadow: '' });
      return;
    }
    $avatar.attr('src', data.img);
    if (data.border) $avatar.css('border', data.border);
    if (data.shadow) $avatar.css('box-shadow', data.shadow);
  }

  function lookupUser(q) {
    $.get(c.searchUrl, { loginSearch: 1, q: q })
      .done(function (r) {
        try { setAvatar(typeof r === 'string' ? JSON.parse(r) : r); }
        catch (e) { setAvatar(null); }
      })
      .fail(function () { setAvatar(null); });
  }

  $username.on('input', function () {
    var q = $(this).val().trim();
    clearTimeout(searchTimer);
    if (!q) return setAvatar(null);
    searchTimer = setTimeout(function () { lookupUser(q); }, 250);
  });

  $('#toggle-password').on('click', function () {
    var show = $password.attr('type') === 'password';
    $password.attr('type', show ? 'text' : 'password');
    $(this).find('i').toggleClass('fa-eye fa-eye-slash');
  });

  function showSuccess(msg) {
    $('#success-avatar').attr('src', $avatar.attr('src'));
    $('#success-message').text(msg);
    $('#login-card').addClass('is-hidden');
    $success.prop('hidden', false).addClass('is-visible');
    setTimeout(function () { location.href = c.redirectUrl || '/app/Staff.Dashboard?welcome=1'; }, 1400);
  }

  $form.on('submit', function (e) {
    e.preventDefault();
    alertMsg('', false);
    var user = $username.val().trim();
    var pass = $password.val();
    var ok = true;
    if (!user) { fieldState($username, $('#username-status'), false); ok = false; }
    else fieldState($username, $('#username-status'), true);
    if (!pass) { fieldState($password, $('#password-status'), false); ok = false; }
    else fieldState($password, $('#password-status'), true);
    if (!ok) return alertMsg('Fill in both fields.', true);

    loading(true);
    $.post(c.loginUrl, $form.serialize())
      .done(function (r) {
        loading(false);
        r = (r || '').trim();
        if (r === 'good') return showSuccess('Welcome back, ' + cap(user));
        if (r === 'suspend') return alertMsg('Account suspended.', true);
        alertMsg('Wrong username or password.', true);
        $submit.addClass('is-error');
        setTimeout(function () { $submit.removeClass('is-error'); }, 2500);
      })
      .fail(function () {
        loading(false);
        alertMsg('Could not reach the server. Try again.', true);
      });
  });

  var banner = document.getElementById('gdpr-banner');
  if (banner) {
    requestAnimationFrame(function () { banner.classList.add('is-visible'); });
    banner.querySelector('.gdpr-banner__btn').addEventListener('click', function () {
      var d = new Date();
      d.setDate(d.getDate() + (c.gdprDays || 365));
      document.cookie = 'gdpr_consent=1;path=/;expires=' + d.toUTCString() + ';SameSite=Lax';
      banner.remove();
    });
  }
})(jQuery);
