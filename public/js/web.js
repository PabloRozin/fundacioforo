$(document).ready(function()
{
  on_scroll();
  resize();
  menu();
  productos();
  ajax_form();
  carrito();
  magnific_popup();

  $('.send-to-print').bind('click', function() {
    $('body').addClass('pdfprint');
    $('body').addClass('moznomarginboxes');
    $('body').addClass('mozdisallowselectionprint');
    $('#noty_top_layout_container').hide();
    window.print();
    $('body').removeClass('pdfprint');
    $('body').removeClass('moznomarginboxes');
    $('body').removeClass('mozdisallowselectionprint');
  });

  $('.toggle-multiselect').each(function()
  {
    $(this).bind('click', function()
    {
      var target = $(this).attr('data-target');

      if ($(this).hasClass('checked'))
      {
        $('.'+target).prop('checked', false);
        $(this).removeClass('checked');
      }
      else
      {
        $('.'+target).prop('checked', true);
        $(this).addClass('checked');
      }
    });
  });

  $('.hc-item').each(function()
  {
    var item = $(this);

    item.find('.hc-item-toggle').bind('click', function()
    {
      var button = $(this);

      if (button.hasClass('open'))
      {
        button.removeClass('open');
        item.find('.hidden').removeClass('open');
      }
      else
      {
        button.addClass('open');
        item.find('.hidden').addClass('open');
      }
    });
  });

  $('.dropzone').each(function()
  {
    var action = $(this).attr('data-action');
    var token = $(this).attr('data-token');
    var name = $(this).attr('data-name');
    var path = $(this).attr('data-path');
    if ( ! $(this).hasClass('dropzoned'))
    {
      $(this).addClass('dropzoned')

      $(this).dropzone({
        url: action,
        params: {
          '_token': token,
        },
        renameFile: function(file) {
          var d = new Date();
          return d.getTime()+'-'+slugify(file.name.split('.')[0])+'.'+file.name.split('.')[1];
        },
        accept: function(file, done) {
          $('.dropzone-items-'+name).append('<input type="hidden" name="dropzone_'+name+'[]" value="https://s3.us-east-2.amazonaws.com/hcdigital/hc/'+file.upload.filename+'">');
          console.log(file);
          done();
        },
        dictDefaultMessage: 'Arrastrar archivos aquí.'
      });
    }
  });

  $('.radio-text').each(function()
  {
    $(this).focusin(function() {
      $($(this).attr('data-target')).prop("checked", true);
    });
  });
  $('.radio_options').each(function()
  {
    var radioCont = $(this);

    radioCont.find('input').change(function()
    {
      var input = $(this);

      radioCont.find('.radio-text').each(function()
      {
        var textarea = $(this);

        if(input.val() != textarea.attr('data-value'))
        {
          textarea.find('textarea').remove();
        }
        else
        {
          textarea.append('<input style="width:auto;position:absolute;top:-5px;left:70px;" type="text" required data-value="'+textarea.attr('data-value')+'" data-target="'+textarea.attr('data-target')+'" name="'+textarea.attr('data-name')+'" placeholder="Tipo de consulta">');
        }
      });
    });
  });
});

function slugify(string) {
  return string
    .toString()
    .trim()
    .toLowerCase()
    .replace(/\s+/g, "-")
    .replace(/[^\w\-]+/g, "")
    .replace(/\-\-+/g, "-")
    .replace(/^-+/, "")
    .replace(/-+$/, "");
}

$(window).resize(function()
{
  resize();
});

$(window).load(function()
{
  resize();
});

$(window).scroll(function()
{
  on_scroll();
});

$(document).on("keypress", ":input", function(event)
{
    if (event.keyCode == 13)
    {
      if($(this).hasClass('preventSubmiyOnEnter'))
      {
          event.preventDefault();
      }
    }
});

$(document).keyup(function(event)
{
  if (event.keyCode == 27)
  {
    close_menues('');
    close_filters();
  }
});

function on_scroll()
{
  scrolled = $(document).scrollTop();

  if(scrolled > 0)
  {
    $('body').addClass('scrolled');
  }
  else
  {
    $('body').removeClass('scrolled');
  }
}

function resize()
{
  full_size();
  map_resize();
  same_height();
}

function same_height()
{
  $('.same-height').each(function()
  {
    height = 0;

    $(this).find('.same-height-item').each(function() {
      $(this).css('height', 'auto');
      if (height < $(this).height()) {
        height = $(this).height();
      }
    });

    $(this).find('.same-height-item').each(function() {
      $(this).css('height', height);
    });
  });
}

function setup_map()
{
  geocoder = new google.maps.Geocoder();

  geocoder.geocode({'address': $('#google_map').attr('data-address')}, function(results, status)
  {
    if (status == google.maps.GeocoderStatus.OK)
    {
      var gmap_hq = results[0].geometry.location;
      var mapDiv = document.getElementById('google_map');
      var myOptions = { scrollwheel: false, zoom: 14, center: gmap_hq, disableDefaultUI: false, mapTypeId: google.maps.MapTypeId.ROADMAP }
      map = new google.maps.Map(mapDiv, myOptions);
      var iconSize = new google.maps.Size(50, 50, 'px', 'px');
      var iconAnchor = new google.maps.Point(18, 56);
      var Icon = new google.maps.MarkerImage('/assets/images/marcador-mapa.png', null, null, iconAnchor, iconSize);
      var gmapMarker = new google.maps.Marker( { position: gmap_hq, map: map, icon: Icon });
    }
  });
}

function map_resize()
{
  if(windowWidth > 768)
  {
    $('#map').css('width', windowWidth + 'px');
    $('#map').css('margin-left', ( - Math.ceil(windowWidth / 2)) + 'px');
  }
  else
  {
    $('#map').css('width', (windowWidth - 15) + 'px');
    $('#map').css('margin-left', (( - Math.ceil(windowWidth / 2)) - 15) + 'px');
  }
}

function full_size()
{
  windowHeight = $(window).height();
  windowWidth = $(window).width();

  $('.full_size').each(function ()
  {
    var container = $(this);

    if ( ! container.hasClass('full_size_only_on_desktop') || windowWidth > 800)
    {
      container.css('height', windowHeight);
    }
    else
    {
      container.css('height', 'auto');
    }

    var content = container.find('.vertical_align');

    if(content.length)
    {
      contentHeight = Math.ceil((container.height() - content.height()) / 2);
      content.css('top', contentHeight);
    }
  });
}

function magnific_popup()
{
  $('.magnific_popup_galery').each(function()
  {
    if( ! $(this).hasClass('magnific_popup_galery_done'))
    {
      $(this).addClass('magnific_popup_galery_done')
      $(this).magnificPopup(
      {
        delegate: 'a.magnific_popup',
        type: 'image',
        gallery: {
          enabled:true
        }
      });
    }
  });

  $('.magnific_popup_photo').each(function()
  {
    if( ! $(this).hasClass('magnific_popup_photo_done'))
    {
      $(this).addClass('magnific_popup_photo_done')
      $('.magnific_popup_photo').magnificPopup({type:'image'});
    }
  });
}

function responsive_images_setup()
{
  $('img[data-src]').each(function()
  {
    if(typeof($(this).attr('data-original-src')) === 'undefined')
    {
      original_src = $(this).attr('src');
      $(this).attr('data-original-src', original_src);
    }
  });

  responsive_images();
}

function responsive_images()
{
  windowWidth = $(window).width();

  $('img[data-src]').each(function()
  {
    var responsive_images = [];

    var new_image = [];
    new_image['width'] = 0;
    new_image['url'] = $(this).attr('data-original-src');

    responsive_images.push(new_image);

    var data_images = $(this).attr('data-src');
    data_images = data_images.split(',');

    for(i = 0; i < data_images.length; i++)
    {
      data_image = data_images[i].split('|');

      var new_image = [];

      new_image['width'] = data_image[0];
      new_image['url'] = data_image[1];

      responsive_images.push(new_image);
    }

    var new_url;

    for(j = 0; j < responsive_images.length; j++)
    {
      if(windowWidth > responsive_images[j]['width'])
        new_url = responsive_images[j]['url'];
    }

    $(this).attr('src', new_url);
  });
}

function close_menues(actual)
{
  if (actual != 'report')
  {
    $('.popup').removeClass('open');
    $('.popup-report').removeClass('open');
    $('.act-report').removeClass('open');
  }
  if (actual != 'report-one')
  {
    $('.popup').removeClass('open');
    $('.popup-report-one').removeClass('open');
    $('.act-report-one').removeClass('open');
  }
  if (actual != 'search')
  {
    $('.popup').removeClass('open');
    $('.popup-search').removeClass('open');
    $('.act-search').removeClass('open');
  }
  if (actual != 'menu')
  {
    $('#header .menu').removeClass('open');
    $('#header .act-menu').removeClass('open');
  }
}

function menu()
{
  $('.popup-close').bind('click', function()
  {
    close_menues('');
  });

  $('.act-report-one').bind('click', function()
  {
    var that = $(this);
    var id = that.attr('data-id');
    var name = that.attr('data-name');
    console.log(id + ' ' + name);

    close_menues('report-one');

    if ($('.act-report-one').hasClass('open'))
    {
      $('.popup').removeClass('open');
      $('.popup-report-one').removeClass('open');
      that.removeClass('open');
    }
    else
    {
      $('.popup').addClass('open');
      $('.popup-report-one').addClass('open');
      $('.popup-report-one .report-data-name').empty().append(name);
      $('.popup-report-one form').attr('action', $('.popup-report-one form').attr('action') + '/' + id);
      resize();
      that.addClass('open');
      $('.popup-report-one input').val('');
      $('.popup-report-one .focus').focus();
    }
  });

  $('.act-report').bind('click', function()
  {
    close_menues('report');

    if ($('.act-report').hasClass('open'))
    {
      $('.popup').removeClass('open');
      $('.popup-report').removeClass('open');
      $('.act-report').removeClass('open');
    }
    else
    {
      $('.popup').addClass('open');
      $('.popup-report').addClass('open');
      resize();
      $('.act-report').addClass('open');
      $('.popup-report input').val('');
      $('.popup-report .focus').focus();
    }
  });

  $('.act-search').bind('click', function()
  {
    close_menues('search');

    if ($('.act-search').hasClass('open'))
    {
      $('.popup').removeClass('open');
      $('.popup-search').removeClass('open');
      $('.act-search').removeClass('open');
    }
    else
    {
      $('.popup').addClass('open');
      $('.popup-search').addClass('open');
      resize();
      $('.act-search').addClass('open');
      $('.popup-search input').val('');
      $('.popup-search .focus').focus();
    }
  });

  $('.act-menu').bind('click', function()
  {
    close_menues('menu');

    if ($('#header .act-menu').hasClass('open'))
    {
      $('#header .menu').removeClass('open');
      $('#header .act-menu').removeClass('open');
    }
    else
    {
      $('#header .menu').addClass('open');
      $('#header .act-menu').addClass('open');
    }
  });


  $('.hash-link').click(function(e)
  {
    e.preventDefault();

    hash = '#'+$(this).attr('href').substring($(this).attr('href').indexOf('#')+1);

    history.pushState(null, null, hash);

    $("html, body").stop().animate({ scrollTop: ($(hash).offset().top - 60) }, 500);

    $('.navbar-collapse').attr('aria-expanded','false');
    $('.navbar-collapse').removeClass('in');
  });
}

function productos()
{
  $('#form_productos_page select').change(function()
  {
    window.location.replace($('#form_productos_page').attr('action') + '/' +  $(this).val());
  });
}

function ajax_form()
{
  $(document).on('click','.ajax-form-div button',function(e)
  {
    ajax_form_send($(this), $(this).parent().attr('data-action'), '');
  });

  $(document).on('submit','form.ajax-form',function(e)
  {
    e.preventDefault();

    thisForm = $(this);

    ajax_form_send(thisForm, thisForm.attr('action'), $(this).serialize());
  });
}

function ajax_form_send(form, action, data)
{
  //form.find('.formSubmit').attr('disabled', 'disabled');
  $('body').prepend('<div id="loading" style="background:url(\'/img/loading.gif?1\') center center no-repeat rgba(255, 255, 255, 0.8);background-size:200px 200px;position:fixed;top:0;left:0;right:0;bottom:0;z-index:100000"></div>');

  $.ajax({
    url: action,
    type: "POST",
    timeout: 60000,
    data: data,
    success: function (data)
    {
      //form.find('.formSubmit').attr('disabled', 'false');
      $('#loading').remove();

      if(typeof data.error !== 'undefined' && data.error === true)
      {
        send_msj('error', data.message);
      }
      else if(typeof data.message !== 'undefined')
      {
        send_msj('success', data.message);

        if(typeof data.action !== 'undefined' && data.action == 'showFormMessage')
        {
          thisForm.find('.cont').remove();
          thisForm.find('.message').show();
        }
      }

      if(typeof data.redirect !== 'undefined')
      {
        window.location.replace(data.redirect);
      }

      if(typeof data.function !== 'undefined')
      {
        window[data.function](data.function_data);
      }
    },
    error: function (xhr, settings, exception)
    {
      //form.find('.formSubmit').attr('disabled', 'false');
      $('#loading').remove();

      send_msj('error', 'Hubo un error interno, por favor volvé a intentarlo.');
    }
  });
}

function updateCarritoTop(data)
{
  $('.update_cart_items').empty().append(data.items);
  $('.update_cart_price').empty().append(data.price);
}

function deleteFromCarrito(data)
{
  $('.carrito #'+data.rowid).remove();

  updateCarrito();
}

function carrito()
{
  $('.cart-item .cart-qty').keyup(function()
  {
    updateCarritoItem($(this));
  });
  $('.cart-item .cart-qty').change(function()
  {
    updateCarritoItem($(this));
  });

  $('#carrito_envio').change(function()
  {
    if($('#carrito_envio').val() == 'entrega_a_domicilio')
    {
      $('.carrito_envio-entrega_a_domicilio').show()
      $('.carrito_envio-retira').hide()
    }
    else
    {
      $('.carrito_envio-retira').show()
      $('.carrito_envio-entrega_a_domicilio').hide()
    }
  });
}

function updateCarritoItem(that)
{
  item = $('#'+that.attr('data-target'));
  subtotalItem = item.find('.cart-subtotal');

  subtotal = parseFloat(item.attr('data-price')) * that.val();

  subtotalItem.attr('data-price', subtotal);
  subtotalItem.empty().append(subtotal);

  updateCarrito();
}

function updateCarrito()
{
  totalItem = $('.cart-total');
  totalItemInput = $('.cart-total-input');

  total = 0;

  $('.cart-item .cart-subtotal').each(function()
  {
    total = total + parseFloat($(this).attr('data-price'));
  });

  total_items = 0;

  $('.cart-item .cart-qty').each(function()
  {
    total_items = total_items + parseFloat($(this).val());
  });

  totalItem.empty().append(total);
  totalItemInput.val(total);


  $('.update_cart_items').empty().append(total_items);
  $('.update_cart_price').empty().append(total);
}

function clearForm(data)
{
  $(data.form+' input[type="text"]').val('');
  $(data.form+' input[type="email"]').val('');
  $(data.form+' input[type="radio"]').attr('checked',false);
  $(data.form+' input[type="checkbox"]').attr('checked',false);
  $(data.form+' textarea').val('');
  $(data.form+' select').each(function(){ $(this).val('').val($(this).val()); });
}

function send_msj(type, text)
{
  noty({
    layout: 'top',
    theme: 'heniaxTheme',
    text: text,
    type: type,
    timeout: 3000,
    killer: true
  });
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
