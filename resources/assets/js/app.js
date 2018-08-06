jQuery(document).ready(function($) {


  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Date picker
  $('#project_start').datetimepicker({
    format: 'L'
  });

  $('#project_end').datetimepicker({
    format: 'L'
  });

  $('#join_deadline').datetimepicker({
    format: 'L'
  });

  $('.evaluation-dates').datetimepicker({
    format: 'L'
  });

  $('#project_start').datetimepicker();
  $('#project_end').datetimepicker({
    useCurrent: false //Important! See issue #1075
  });
  $("#project_start").on("dp.change", function (e) {
    $('#project_end').data("DateTimePicker").minDate(e.date);
  });
  $("#project_end").on("dp.change", function (e) {
    $('#project_start').data("DateTimePicker").maxDate(e.date);
  });



  // Tags input
  $('input #tags').on('change', function(event) {
    var $element = $(event.target),
      $container = $element.closest('.example');

    var val = $element.val();
    if (val === null)
      val = "null";
    $('code', $('pre.val', $container)).html( ($.isArray(val) ? JSON.stringify(val) : "\"" + val.replace('"', '\\"') + "\"") );
    $('code', $('pre.items', $container)).html(JSON.stringify($element.tagsinput('items')));
  }).trigger('change');


  // Sweet alert
  $("button#delete").on("click", function(e){

    swal({
        title: window.Laravel.are_you_sure_notification,
        text: window.Laravel.cannot_restore_notification,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: window.Laravel.yes_delete,
        cancelButtonText: window.Laravel.no,
        closeOnConfirm: false
      },
      function(){
        $(e.target).prev('.delete-project').submit();

      });

  });


  $("button#delete-user-button").on("click", function(e){

    swal({
        title: window.Laravel.are_you_sure_notification,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: window.Laravel.yes_delete,
        cancelButtonText: window.Laravel.no,
        closeOnConfirm: false
      },
      function(){
        $(e.target).prev('.delete-user').submit();

      });

  });


  $("a#groups-finish-button").on("click", function(e){
    if(!$("a#groups-finish-button").hasClass('not-empty')){
      e.preventDefault();

      swal({
        title: window.Laravel.finish_project_notification,
        type: "info",
        confirmButtonText: window.Laravel.yes,
        closeOnConfirm: false
      });
    }


  });

  // Select2
  $(".js-example-basic-multiple").select2();



  // Select2 Ajax - attaching users to project team manually
  $(".js-users-data-ajax").select2({
    placeholder: window.Laravel.name_or_email_placeholder,
    language: { inputTooShort: function () { return window.Laravel.three_or_more_char; } },
    allowClear: true,
    ajax: {
      url: window.Laravel.search_user_api_url,
      dataType: 'json',
      delay: 250,
      method: 'POST',
      data: function (params) {
        return {
          q: params.term, // search term
          project_id: $('.js-users-data-ajax').attr("project-id"),
          page: params.page
        };
      },
      processResults: function (data) {
        return {
          results: $.map(data, function (item) {
            return {
              text: (item.full_name ? item.full_name : item.name) + ' ('+(item.contact_email? item.contact_email : item.email)+')',
              id: item.id
            }
          })
        };
      },
      cache: false
    },
    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
    minimumInputLength: 3
  });

  //Smooth scroll for front page
  $('body.frontpage a[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });



  //Go from front page to certain tab in FAQ page
  var hash = window.location.hash;
  if(hash.startsWith('#item')){
    hash && $('ul.nav a[href="' + hash + '"]').tab('show');
  }

  //Search form
  $('.search-panel .navbar-nav').find('a').click(function(e) {
    e.preventDefault();
    var param = $(this).attr("href").replace("#","");
    var concept = $(this).text();
    $('.search-panel span#search_concept').text(concept);
    $('.form-group #search_param').val(param);
  });

  var selector = '.search-panel .navbar-nav li';

  $(selector).on('click', function(){
    $(selector).removeClass('active');
    $(this).addClass('active');
  });



  //Panel used in search view to show a list of member emails
  $('.panel-heading span.clickable').parents('.panel').find('.panel-body').slideUp();

  $('.panel-heading span.clickable').on("click", function(e){
    var $this = $(this);
    if(!$this.hasClass('panel-collapsed')) {
      $this.parents('.panel').find('.panel-body').slideUp();
      $this.addClass('panel-collapsed');
      $this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
    } else {
      $this.parents('.panel').find('.panel-body').slideDown();
      $this.removeClass('panel-collapsed');
      $this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
    }
  });


  //Facebook sharing button handler, Facebook SDK for JavaScript
  function postToFeed(url){
    var obj = {method: 'feed',link: url};
    function callback(response){}
    FB.ui(obj, callback);
  }

  $('.btnShare').click(function(){
    elem = $(this);
    postToFeed(elem.prop('href'));

    return false;
  });


  //Form/Project language selection
  $('#project_in_english').on('click', function () {
    $('.form_english').toggleClass("disabledForm");
    //$('#project_in_english').children('input').trigger('click');
  });
  $('#project_in_estonian').on('click', function () {
    $('.form_estonian').toggleClass("disabledForm");
    //$('#project_in_estonian').children('input').trigger('click');
  });

  $('#submit_project').on('click', function (e) {
    e.preventDefault();
    var co_supervisors = []
    for (var i=0;i<$('.co_supervisor').length;i++)
    {
      co_supervisors.push($($('.co_supervisor')[i]).val());
    }
    $('#co_supervisors').val(JSON.stringify(co_supervisors));
    $('#project_form').submit();
  });

  $('.study_term_button').on('click', function () {
    $('.study_term_button').removeClass('btn-info');
    $('.study_term_button').removeClass('btn-default');
    $(this).removeClass('btn-default');
    $(this).addClass('btn-info');
    $('#'+$(this).attr('id')+'_radio').prop('checked', true);
  });

  //Learning outcomes show/hide
  $('#open_learning_outcomes').on('click', function () {
    $('#learning_outcomes').toggle(100);
    $('#open_learning_outcomes').toggle(1);
    $('#close_learning_outcomes').toggle(1);
  });

  $('#close_learning_outcomes').on('click', function () {
    $('#learning_outcomes').toggle(100);
    $('#close_learning_outcomes').toggle(1);
    $('#open_learning_outcomes').toggle(1);
  });


  //Typeahead styling
  $('body').on("mouseover", ".tt-suggestion", function () {
    $('.tt-suggestion').removeClass('tt-cursor');
    $(this).addClass('tt-cursor');
  });

  //Adding Typeahead to tags input in new project form
  $.ajax({
    url: window.Laravel.base_path+'/all_tags'
  }).done(function (tags) {
    var list_of_tags_et = new Bloodhound({
      local: tags['et'],
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      datumTokenizer: Bloodhound.tokenizers.whitespace
    });
    var list_of_tags_en = new Bloodhound({
      local: tags['en'],
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      datumTokenizer: Bloodhound.tokenizers.whitespace
    });
    addTag('et');
    addTag('en');
    $('input[name=tags_et]').typeahead({hint:false,minLength:1,highlight:true},{name:'tags_et',source:list_of_tags_et});
    $('input[name=tags_en]').typeahead({hint:false,minLength:1,highlight:true},{name:'tags_en',source:list_of_tags_en});
  });

  function addTag (language) {
    $('input[name=tags_'+language+']').on('keypress', function (event) {if (event.keyCode == 13 || event.which == 13) {
      newTag = document.createElement('span');
      newTag.className = 'tag_output';
      newTag.innerHTML = $('input[name=tags_'+language+']').val() + '<span class=\'glyphicon glyphicon-remove\'></span>';
      $(newTag).children('span.glyphicon-remove').on('click', function () {
        $(this).parent().remove();
      });
      $('#tags_'+language+'_output').append(newTag);
    }});
  }

  $('#add_meeting_et').on('click', function () {$('#other_meetings_et').append(getMeetingFieldToAdd('et'));});
  $('#add_meeting_en').on('click', function () {$('#other_meetings_en').append(getMeetingFieldToAdd('en'));});
  $('#remove_meeting_et').on('click', function () {$('#other_meetings_et').children(':last-child').remove()});
  $('#remove_meeting_en').on('click', function () {$('#other_meetings_en').children(':last-child').remove()});
  $('#add_cosupervisor').on('click', function () {
    newCosupervisor = document.createElement('input');
    newCosupervisor.className ="form-control co_supervisor";
    newCosupervisor.type="text";
    $(this).parent().prev('#co_supervisor_div').append(newCosupervisor)
  });
  $('#remove_cosupervisor').on('click', function () {$(this).parent().prev('#co_supervisor_div').children(':last-child').remove()});

  function getMeetingFieldToAdd (language) {
    outerDiv = document.createElement('div');
    outerDiv.className = 'row';
    iconDiv = document.createElement('div');
    iconDiv.className = 'col-lg-3';
    iconCalendarDiv = document.createElement('div');
    iconCalendarDiv.className = 'col-lg-12';
    iconCalendar = document.createElement('span');
    iconCalendar.className = 'glyphicon glyphicon-calendar';
    iconCalendar.style = 'font-size:75px;';
    iconSubCalendarTextDiv = document.createElement('div');
    iconSubCalendarTextDiv.className = 'col-lg-12';
    iconSubCalendarTextDiv.innerHTML = 'dd/mm';
    textareaDiv = document.createElement('div');
    textareaDiv.className = 'col-lg-8';
    textarea = document.createElement('textarea');
    textarea.className = 'meetings_'+language;
    textarea.style = 'width: 100%;';
    textarea.rows = '5';
    iconDiv.append(iconCalendarDiv);
    iconCalendarDiv.append(iconCalendar);
    iconDiv.append(iconSubCalendarTextDiv);
    textareaDiv.append(textarea);
    outerDiv.append(iconDiv);
    outerDiv.append(textareaDiv);
    return outerDiv;
  }


  //TinyMC
  tinyMCE.baseURL = window.Laravel.base_path+"/js/tinymce";

  tinymce.init({
    mode : "textareas",
    theme : "modern",
    language: window.Laravel.language,
    plugins: ["link", "paste"],
    menubar: false,
    toolbar: "link",
    removeformat: [
      {selector: 'b,strong,em,i,font,u,strike', remove : 'all', split : true, expand : false, block_expand: true, deep : true},
      {selector: 'span', attributes : ['style', 'class'], remove : 'empty', split : true, expand : false, deep : true},
      {selector: '*', attributes : ['style', 'class'], split : false, expand : false, deep : true,}
    ],
    selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
    editor_selector : "mceSimpleLink",
    paste_as_text: true
  });

  tinyMCE.init({
    mode : "textareas",
    language: window.Laravel.language,
    plugins: ["link", "lists", "paste", "noneditable", "preventdelete"],
    height : "350",
    removeformat: [
      {selector: 'b,strong,em,i,font,u,strike', remove : 'all', split : true, expand : false, block_expand: true, deep : true},
      {selector: 'span', attributes : ['style', 'class'], remove : 'empty', split : true, expand : false, deep : true},
      {selector: '*', attributes : ['style', 'class'], split : false, expand : false, deep : true}
    ],
    toolbar: "redo undo bold italic numlist bullist link",
    menubar: false,
    editor_selector : "mceSimple",
    paste_as_text: true
  });

  $('#submit-project-button').on('click', function (e) {
    e.preventDefault();
    tinyMCE.triggerSave();
    tinyMCE.get('project_outcomes').setContent(removeExcessWhitespaceFromString(tinyMCE.get('project_outcomes').getContent().split('&nbsp;').join(' ')));
    tinyMCE.get('novelty_desc').setContent(removeExcessWhitespaceFromString(tinyMCE.get('novelty_desc').getContent().split('&nbsp;').join(' ')));
    tinyMCE.get('aim').setContent(removeExcessWhitespaceFromString(tinyMCE.get('aim').getContent().split('&nbsp;').join(' ')));
    tinyMCE.get('description').setContent(removeExcessWhitespaceFromString(tinyMCE.get('description').getContent().split('&nbsp;').join(' ')));
    tinyMCE.triggerSave();
    $('#project-form').submit();
  });


  function removeExcessWhitespaceFromString (string) {
    var stringLength = string.length;
    var outputString = "";
    for (var i=0;i<stringLength-1;i++) {
      if (!(string[i] === " " && string[i+1] === " " || string[i] === " " && string[i+1] === "<" || string[i-1] === ">" && string[i] === " ")) {
        outputString += string[i];
      }
    }
    if (string[stringLength] !== " ") {
      outputString += string[stringLength-1]
    }
    // Removing empty tags
    var helper = $('<div>').append(outputString);
    removeEmptyIfNoChildren(helper);
    outputString = helper.prop('innerHTML');
    return outputString;
  }

  function removeEmptyIfNoChildren (element) {
    if (element.children().length > 0) {
      element.children().each(function () {
        removeEmptyIfNoChildren($(this));
      });
    }
    if (element.html() === "") {
      element.remove();
    }
  }

  //Front page logo translation
  if(window.Laravel.language == 'en'){
    $('.block01.block01b > .pad').css("background", 'url(css/bg05_en.png) no-repeat 50% 50%');
  }else{
    $('.block01.block01b > .pad').css("background", 'url(css/bg05.png) no-repeat 50% 50%');
  }


  //Select project share url on click on input
  $("input[name='share_url']").on("click", function () {
    $(this).select();
  });


  if($("#project_all_members").length) {

    //Project id used by ProjectModerator middleware
    var url = window.location.pathname;
    var segments = url.split( '/' );
    var project_id =  segments[segments.length-2];


    //Drag and drop project group members functionality
    Sortable.create(project_all_members, {
      group: { name: "project-all-members", pull: true, put:true },
      animation: 150,
      handle: '.drag-handle'
    });

    var el = $('.project-group');
    $(el).each(function (i,e) {
      var sortable = Sortable.create(e, {
        group: {
          name: el.attr('group-id'),
          pull: true,
          put: function () {
            return true;
          }
        },
        animation: 150,
        handle: '.drag-handle',
        onAdd: function (evt) {

          var itemEl = evt.item;  // dragged HTMLElement
          var from = evt.from;


          $.ajax({
            url: window.Laravel.add_user_to_group_api_url,
            dataType: 'json',
            delay: 250,
            method: 'POST',
            cache: false,
            data: {

              id: project_id,
              to: $(itemEl).parent().attr('group-id'),
              from: $(from).attr('group-id'),
              user: $(itemEl).attr('user-id')

            }
          }).done(function( msg ) {
            console.log(msg);
            if($('a#groups-finish-button').length){
              $('a#groups-finish-button').addClass('not-empty');
            }


          });

        }

      });
    })
  }


  //Copy user main TLU email into contact email fields, used on profile page
  $("#filler").click(fillValues);


  function fillValues() {
    var value = $("#tlu_email").val();
    var fields= $(".contact-email");
    fields.each(function (i) {
      $(this).val(value);
    });
  }



  //Add custom institution field in user registration form
  $('#institution').on('change',function(){
    if( $(this).val() === window.Laravel.other_institution){
      $("#other-institution").show()
    }
    else{
      $("#other-institution").hide()
    }
  });




  // Add new group links input field
  var add_links_field_buttons = $(".add_links_field_button");

  add_links_field_buttons.each(function (i, obj) {
    var group_id = $(obj).attr("group-id");
    $(obj).click(function(e){
      e.preventDefault();
      var $div = $(this).next( ".group-materials").children().last();

      var num = $div.prop("id");

      num = parseInt(num.split("_").pop());


      var clone = $($div).clone();
      clone.prop('id', 'group_materials_'+group_id+'_'+(num+1));
      clone.find("input").val("");
      clone.insertAfter($div);

      var tagsinput = clone.find('.tags');
      clone.find('.bootstrap-tagsinput').remove();

      $(tagsinput).attr('name', 'group_material_tags['+group_id+'][]');
      $(tagsinput).addClass('form-control tags');
      tagsinput.tagsinput();
      tagsinput.tagsinput('removeAll');





    });

  });




  // Remove group links input field
  var remove_links_field_button = $(".remove_links_field_button");

  remove_links_field_button.each(function (i, obj) {
    var group_id = $(obj).attr("group-id");
    $(obj).click(function(e){

      e.preventDefault();
      var $div = $(this).parent().children(":last-child");

      var num = $div.prop("id");

      num = parseInt(num.split("_").pop());

      if(num>0){
        $div.remove();
      }






    });
  });



  $('[data-toggle="popover"]').popover();


  $('#clear-embedded').click(function(){
    $('#embedded')
      .val('')
  });


  $('#clear-group-embedded').click(function(){
    $(this).prev('input').val('');
  });


  $('body').tooltip({
    selector: '[rel="tooltip"]'
  });


  //Group title renaming;
  $('.group-name').editable({
    send:'always',
    ajaxOptions: {
      dataType: 'json',
      type: 'post',

    },
    params: function(params) {
      //originally params contain pk, name and value
      params.id = $(this).closest("div").attr("project-id");
      return params;
    }

  });





});

Dropzone.autoDiscover = false;
Dropzone.prototype.defaultOptions.dictRemoveFile = window.Laravel.remove_file_button;

var dropzones = $(".dropzone");
dropzones.each(function (i) {
  if (this.id.indexOf("presentationUpload") !== -1) {
    var resourceName = "Poster";
    var accepted = "application/pdf";
    var maxNumberOfFiles = 1;
    var group_id = parseInt( $(this).prop("id").match(/\d+/g), 10 );

    var project_id = $("#presentationUpload"+group_id).attr("project-id");
    var dropzone_name = "#presentationUpload"+group_id;
  } else {
    var resourceName = "Materials";
    var accepted = "";
    var maxNumberOfFiles = 100;
    var group_id = parseInt( $(this).prop("id").match(/\d+/g), 10 );

    var project_id = $("#materialsUpload"+group_id).attr("project-id");
    var dropzone_name = "#materialsUpload"+group_id;
  }
  if (this.getAttribute('auth') == 'student') {
    var routeTo = 'finish';
  } else {
    var routeTo = 'project';
  }

    var myDropzone = new Dropzone(dropzone_name, {
    url: window.Laravel.base_path+"/"+routeTo+"/"+project_id+"/finish/upload"+resourceName,
    params: {
      id: project_id,
      _token: window.Laravel.csfr_token,
      group_id: group_id
    },
    timeout: 0,
    parallelUploads: 1,
    paramName: "file",
    acceptedFiles: accepted,
    maxFiles: maxNumberOfFiles,
    maxFilesize: 30, // MB

    addRemoveLinks: true,

    init:function() {


      // Add server images
      var myDropzone = this;

      $.get(window.Laravel.base_path+"/"+routeTo+"/"+project_id+"/api/group-"+resourceName+"?groupid="+group_id, function(data) {


        $.each(data.images, function (key, value) {
          var file = {name: value.filename, size: value.size};
          myDropzone.options.addedfile.call(myDropzone, file);
          myDropzone.createThumbnailFromUrl(file, window.Laravel.base_path+"/storage/projects_groups_images/"+group_id+"/"+value.name);
          myDropzone.emit("complete", file);
          var btndelete = file.previewElement.querySelector("[data-dz-remove]");
          btndelete.setAttribute("id", 'delete-media-name-'+value.name);

        });
      });

      this.on("removedfile", function(file) {

        var btndelete = file.previewElement.querySelector("[data-dz-remove]");
        if(btndelete.hasAttribute("id")) {
          var filename = btndelete.getAttribute("id").substr(18);
        }

        $.ajax({
          url: window.Laravel.base_path+"/"+routeTo+"/"+project_id+"/finish/delete"+resourceName,
          dataType: 'json',
          delay: 250,
          method: 'POST',
          cache: false,
          data: {

            id: project_id,
            name: filename,
            group_id: group_id

          }
        }).done(function( msg ) {
          console.log(msg);

        });

      } );
    },
    success: function(file, serverResponse) {
      var fileuploded = file.previewElement.querySelector("[data-dz-name]");
      fileuploded.innerHTML = serverResponse.newfilename;
      var btndelete = file.previewElement.querySelector("[data-dz-remove]");
      btndelete.setAttribute("id", 'delete-media-name-'+serverResponse.newfilename);
      window.alert('Upload successful');
    }
  });
});








/*
  Sending DELETE request without a form
  Example:
  <a href="posts/2" data-method="delete" data-token="{{csrf_token()}}">
 */

(function() {

  var laravel = {
    initialize: function() {
      this.methodLinks = $('a[data-method]');
      this.token = $('a[data-token]');
      this.registerEvents();
    },

    registerEvents: function() {
      this.methodLinks.on('click', this.handleMethod);
    },

    handleMethod: function(e) {
      var link = $(this);
      var httpMethod = link.data('method').toUpperCase();
      var form;

      // If the data-method attribute is not PUT or DELETE,
      // then we don't know what to do. Just ignore.
      if ( $.inArray(httpMethod, ['PUT', 'DELETE']) === - 1 ) {
        return;
      }

      // Allow user to optionally provide data-confirm="Are you sure?"
      if ( link.data('confirm') ) {
        if ( ! laravel.verifyConfirm(link) ) {
          return false;
        }
      }

      form = laravel.createForm(link);
      form.submit();

      e.preventDefault();
    },

    verifyConfirm: function(link) {
      return confirm(link.data('confirm'));
    },

    createForm: function(link) {
      var form =
        $('<form>', {
          'method': 'POST',
          'action': link.attr('href')
        });

      var token =
        $('<input>', {
          'type': 'hidden',
          'name': '_token',
          'value': link.data('token')
        });

      var hiddenInput =
        $('<input>', {
          'name': '_method',
          'type': 'hidden',
          'value': link.data('method')
        });

      return form.append(token, hiddenInput)
        .appendTo('body');
    }
  };

  laravel.initialize();

})();
