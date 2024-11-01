
		(function( $ ) {
			'use strict';
			

			 $(function() {


			 	function startAutocomplete(data, widget){

		    	let headerSearchJS;

		     	const config = {
		              selector: "#"+data.search,
		              placeHolder: "",
		              data: {
		                src: async (query) => {
		                  try {
		                    const source = await fetch(`https://eltiempoen.com:9090/api/wpsearch/?q=${query}`);
		                    const data = await source.json();
		                    return data;
		                  } catch (error) {
		                    return error;
		                  }
		                },

		                keys: ["text"]
		              },
		              debounce: 300,
		              searchEngine: function(query, record){
		                return record
		              },
		              diacritics: true,
		              resultsList: {
		                maxResults: 15,
		                class: 'search-results',
		                element: (list, data) => {
		                  if (!data.results.length) {
		                  }
		                },
		                noResults: false
		              },
		              tabSelect: true,
		              wrapper: false,
		              resultItem: {
		                element: (item, data) =>{ 
		                    item.style = "display: flex; justify-content: space-between;";  
		                    item.innerHTML = `
		                    <span>
		                      ${data.match}
		                    </span>`;
		                }
		                
		              },
		              events: {
		                input: {
		                  blur() {
		                  },
		                 
		                },
		              }
		    	}


		    	headerSearchJS = new autoComplete(config);
		   
			    headerSearchJS.input.addEventListener("selection", function (event) {
			      	const feedback = event.detail;
			      	headerSearchJS.input.blur();
			        const selection = feedback.selection.value[feedback.selection.key];
			        headerSearchJS.input.value = selection;

			      	$("#"+data.location).val(selection).trigger('change');
			      	$("#"+data.search).val(selection).trigger('change');
			      	$("#"+data.cityid).val(feedback.selection.value.id).trigger('change');
			      	$("#"+data.city).val(feedback.selection.value.city).trigger('change');
			      	$("#"+data.state).val(feedback.selection.value.state).trigger('change');
			      	$("#"+data.country).val(feedback.selection.value.country).trigger('change');
			      	$("#"+data.url_en).val(feedback.selection.value.url_en).trigger('change');
			      	$("#"+data.url_es).val(feedback.selection.value.url_es).trigger('change');
				});

			    $(widget).find("input, select").change(function(event) {
				 	
				 	let id = $(this).attr("id");

				 	if(!id){
				 		id = $(this).parent().attr("id");
				 	}

				 	let val
				 	let type = $(event.target).attr('type')
				 	if(!id){
				 		type = 'option'
				 	}

				 	if(type == "checkbox"){
				 		val = $(event.target).prop('checked') ? 'on' : 'off';
				 	}else if(type == 'color'){
				 		val = $(this).val()
				 	}else if(type == 'option'){
				 		id = $(this).parent().attr("id");
				 		val = $(this).val()
				 	}else{
				 		val = $(this).val()
				 	}

				 	$('#'+id+'-prev').text((val).replace(/"/g, '\''));
				});

		    }
		    

		     function init(){

		     	
		     	let WIYCW_admin_widgets = document.querySelectorAll(".WIYCW-admin")

				     WIYCW_admin_widgets.forEach(widget =>{
				     	if(widget.dataset.init == "false" && widget.id.indexOf('__i__') == -1){
				     		widget.dataset.init = true
					     	startAutocomplete({
				     			location: widget.dataset.location,
				     			search: widget.dataset.search,
					     	 	city: widget.dataset.city,
					     	 	cityid: widget.dataset.cityid,
					     	 	state: widget.dataset.state,
					     	 	country: widget.dataset.country,
					     	 	url_en: widget.dataset.url_en,
					     	 	url_es: widget.dataset.url_es,
				     		}, widget);
				     	}
				     	
				     })
		     }
		   		

			  $(document).on('widget-added widget-updated', function(event, widget){
			  	 init()
                });
			 	
			  init()
			})
})( jQuery );
