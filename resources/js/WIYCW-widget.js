(function(){

        		String.prototype.capitalize = function () {
					return this.charAt(0).toUpperCase() + this.slice(1)
				};

				function fetchJSONFile(path, callback, error) {
			        var httpRequest = new XMLHttpRequest();
			        httpRequest.onreadystatechange = function() {
			            if (httpRequest.readyState === 4) {
			                if (httpRequest.status === 200) {

			                  if(httpRequest.responseText){
			                    var data = JSON.parse(httpRequest.responseText);
			                      callback(data);
			                  }
			                    
			                } else {
			                  error();
			                }
			            }
			        };
			        httpRequest.open('GET', path);
			        httpRequest.send(); 
			    }


			    function formatTemp(temp, units){
			    	if(units == 'c'){
				       return Math.round(temp) + 'º';
				    }else{
				       return Math.round((temp * 1.8) + 32) + 'º';
				    }
			    }

			    function formatSpeed(speed,units){
			    	if(units == 'c'){
				        return Math.round(speed) + ' km/h'
				    }else{
				       return Math.round(speed * 0.62137) + ' mph'
				    }
			    }

			    function formatVol(vol,units){
			    	if(units == 'c'){
				        return parseFloat(vol).toFixed(2) + ' mm'
				    }else{
				        return parseFloat((vol / 25.4).toFixed(2)) + ' in'
				    }
			    }

			    function tConvert (time) {
				 
				  time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

				  if (time.length > 1) { 
				    time = time.slice (1);
				    time[5] = +time[0] < 12 ? ' AM' : ' PM';
				    time[0] = +time[0] % 12 || 12; 
				  }
				  return time.join ('');
				}


				let WIYCW_widgets = document.querySelectorAll(".WIYCW-wrapper")


				WIYCW_widgets.forEach(widget =>{

					

					let content = widget.querySelectorAll(".WIYCW-content")[0]

					widget.style.color = widget.dataset.textcolor
					widget.style.backgroundColor = widget.dataset.backgroundcolor
					widget.style.border = "1px solid " + widget.dataset.bordercolor

					let iconsShadowClass = widget.dataset.shadow == "on" ? '-shadow' : ''

					let iconsColorClass = widget.dataset.iconscolor == "dark" ? 'WIYCW-icon-dark' : 'WIYCW-icon-light'
					iconsColorClass += iconsShadowClass
					let iconsColorPath = widget.dataset.weathericonscolor == "color" ? 'color' : 'monocromatic'

					let weatherIconsColorClass = 'WIYCW-icon-color'
					switch(widget.dataset.weathericonscolor){
						case "color":
							weatherIconsColorClass = 'WIYCW-icon-color' + iconsShadowClass
						break;
						case "dark":
							weatherIconsColorClass = 'WIYCW-icon-dark' + iconsShadowClass
						break;
						case "light":
							weatherIconsColorClass = 'WIYCW-icon-light' + iconsShadowClass
						break;
						default:
						weatherIconsColorClass = 'WIYCW-icon-color' + iconsShadowClass
						break;
					}

					let r = ""
					try{
						r = window.btoa(window.location.hostname)
					}catch(er){}



					function getWeatherData(url, error){
						fetchJSONFile(url, function(data){

							if(data.error){
								console.log(data.message)
								error();
								return
							}
							content.innerHTML = ""

							if(widget.dataset.today == "on"){
								const now = document.createElement("div");
								now.classList = "WIYCW-now"

								const now_row1 = document.createElement("div");
								now_row1.classList = "WIYCW-now-row1"

							  	if(widget.dataset.nowicon == "on"){
							  		const icon = document.createElement("div");
									icon.classList = "WIYCW-now-icon"

									const img = document.createElement("img");
									img.src = widget.dataset.path + "/resources/icons/weather-icons/"+iconsColorPath + '/' + data.now.icon + ".svg"
									img.alt = 'Current weather'
									img.style.width = "70px"
									img.style.height = "70px"
									img.classList = weatherIconsColorClass
									icon.appendChild(img)
									now_row1.appendChild(icon)
							  	}
								
								if(widget.dataset.nowtemp == "on"){
									const temp = document.createElement("div");
									temp.innerHTML = formatTemp(data.now.temp, widget.dataset.units)
									temp.classList = "WIYCW-now-temp"
									now_row1.appendChild(temp)
								}

								if(widget.dataset.nowicon == "off" && widget.dataset.nowtemp == "off"){
									now_row1.style.display = "none"
								}

								const now_row_info = document.createElement("div");
								now_row_info.classList = "WIYCW-now-row-info"
								

								if(widget.dataset.nowsunrise == "on"){
									const sunriseIcon = document.createElement("img");
									sunriseIcon.alt = 'Sunrise'
									sunriseIcon.classList = "WIYCW-item-icon " + iconsColorClass
									sunriseIcon.src = widget.dataset.path + "/resources/icons/ui-icons/sunrise.svg"
									sunriseIcon.style.width = "11px"
									sunriseIcon.style.height = "11px"

									const sunrise = document.createElement("div");
									sunrise.classList = "WIYCW-now-row-info-col"

									sunrise.appendChild(sunriseIcon)

									if(widget.dataset.timeformat === 'standard'){
										data.now.sunrise = tConvert(data.now.sunrise);
									}

									sunrise.innerHTML += data.now.sunrise
									now_row_info.appendChild(sunrise)

									const sunsetIcon = document.createElement("img");
									sunsetIcon.alt = 'Sunset'
									sunsetIcon.classList = "WIYCW-item-icon " + iconsColorClass
									sunsetIcon.src = widget.dataset.path + "/resources/icons/ui-icons/sunset.svg"
									sunsetIcon.style.width = "11px"
									sunsetIcon.style.height = "11px"

									const sunset = document.createElement("div");
									sunset.classList = "WIYCW-now-row-info-col"

									if(widget.dataset.timeformat === 'standard'){
										data.now.sunset = tConvert(data.now.sunset);
									}

									sunset.appendChild(sunsetIcon)
									sunset.innerHTML += data.now.sunset
									now_row_info.appendChild(sunset)
								} 

								if(widget.dataset.nowhumidity == "on"){

									const dropIcon = document.createElement("img");
									dropIcon.alt = 'Humidity';
									dropIcon.classList = "WIYCW-item-icon " + iconsColorClass
									dropIcon.src = widget.dataset.path + "/resources/icons/ui-icons/dew-point.svg"
									dropIcon.style.width = "11px"
									dropIcon.style.height = "11px"

									const humidity = document.createElement("div");
									humidity.classList = "WIYCW-now-row-info-col"

									humidity.appendChild(dropIcon)
									humidity.innerHTML += data.now.humidity + "%"
									now_row_info.appendChild(humidity)
								} 

								if(widget.dataset.nowwind == "on"){

									const windRotation = document.createElement("div");
									windRotation.classList = "WIYCW-item-icon " + iconsColorClass
									windRotation.style.width = "11px"
									windRotation.style.height = "11px"
									windRotation.style.display = "flex"

									const arrowIcon = document.createElement("img");
									arrowIcon.alt = 'Wind direction';
									arrowIcon.src = widget.dataset.path + "/resources/icons/ui-icons/arrow.svg"
									arrowIcon.style.width = "11px"
									arrowIcon.style.height = "11px"
									arrowIcon.style.transform = "rotate("+data.now.wind_deg+"deg)"

									windRotation.appendChild(arrowIcon)

									const wind = document.createElement("div");
									wind.classList = "WIYCW-now-row-info-col"

									wind.appendChild(windRotation)
									wind.innerHTML += formatSpeed(data.now.wind_speed, widget.dataset.units)
									now_row_info.appendChild(wind)

								} 
								
								if(widget.dataset.nowpressure == "on"){

									const pressureIcon = document.createElement("img");
									pressureIcon.alt = 'Pressure'
									pressureIcon.classList = "WIYCW-item-icon " + iconsColorClass
									pressureIcon.src = widget.dataset.path + "/resources/icons/ui-icons/pressure.svg"
									pressureIcon.style.width = "11px"
									pressureIcon.style.height = "11px"

									const pressure = document.createElement("div");
									pressure.classList = "WIYCW-now-row-info-col"

									pressure.appendChild(pressureIcon)
									pressure.innerHTML += data.now.pressure + " hPa"
									now_row_info.appendChild(pressure)
								} 

								if(widget.dataset.nowcloudiness == "on"){

									const cloudinessIcon = document.createElement("img");
									cloudinessIcon.alt = 'Cloudiness'
									cloudinessIcon.classList = "WIYCW-item-icon " + iconsColorClass
									cloudinessIcon.src = widget.dataset.path + "/resources/icons/ui-icons/clouds.svg"
									cloudinessIcon.style.width = "11px"
									cloudinessIcon.style.height = "11px"

									const cloudiness = document.createElement("div");
									cloudiness.classList = "WIYCW-now-row-info-col"

									cloudiness.appendChild(cloudinessIcon)
									cloudiness.innerHTML += data.now.cloudiness + "%"
									now_row_info.appendChild(cloudiness)
								} 

								
								now.appendChild(now_row1)
								now.appendChild(now_row_info)
								content.appendChild(now)

							}
							

							
							if(widget.dataset.days !== "off"){
								const forecast = document.createElement("div");
								forecast.classList = "WIYCW-forecast WIYCW-forecast-"+widget.dataset.layout
								let number = 0;
								for(const day of data.forecast){

									number ++

									if(number > widget.dataset.days){
										break
									}
									const row = document.createElement("div");
									row.classList = "WIYCW-forecast-row"
									const date = document.createElement("div");

										if(number == 1){
											date.innerHTML = WIYCW_i18n['today'].capitalize()
										}else if(number == 2){
											date.innerHTML = WIYCW_i18n['tomorrow'].capitalize()
										}else{
											date.innerHTML = (WIYCW_i18n['week_days'][day.wday]).capitalize() + " " + day.day
										}
										 
										date.classList = "WIYCW-forecast-date WIYCW-col-1"
										row.appendChild(date)

									if(widget.dataset.temp == "on"){
										const temp = document.createElement("div");
										temp.classList = "WIYCW-forecast-temp WIYCW-col-1"
										temp.innerHTML = formatTemp(day.temp_max, widget.dataset.units) + " / " + formatTemp(day.temp_min, widget.dataset.units)
										row.appendChild(temp)
									}

									if(widget.dataset.forecasticon == "on"){
										const icon = document.createElement("div");
										icon.classList = "WIYCW-forecast-icon WIYCW-col-1"
										const img = document.createElement("img");
										img.alt = 'Forecast'
										img.src = widget.dataset.path + "/resources/icons/weather-icons/"+ iconsColorPath + '/'+ day.icon + ".svg"
										img.style.width = "30px"
										img.style.height = "30px"
										img.classList = weatherIconsColorClass
										icon.appendChild(img)
										row.appendChild(icon)

									}

									if(widget.dataset.rainchance == "on"){

										const umbrellaIcon = document.createElement("img");
										umbrellaIcon.alt = 'Rain chance'
										umbrellaIcon.classList = "WIYCW-item-icon " + iconsColorClass
										umbrellaIcon.src = widget.dataset.path + "/resources/icons/ui-icons/umbrella.svg"
										umbrellaIcon.style.width = "11px"
										umbrellaIcon.style.height = "11px"

										const pop = document.createElement("div");
										pop.appendChild(umbrellaIcon)
										pop.classList = "WIYCW-forecast-pop WIYCW-col-1"
										pop.innerHTML += Math.round(day.pop*100) + "%"
										row.appendChild(pop)
									}

									if(widget.dataset.rain == "on"){
										const rain = document.createElement("div");
										rain.classList = "WIYCW-forecast-rain WIYCW-col-1"
										rain.innerHTML = formatVol(day.rain, widget.dataset.units)
										row.appendChild(rain)
									}

									if(widget.dataset.wind == "on"){

										const windRotation = document.createElement("div");
										windRotation.classList = "WIYCW-item-icon " + iconsColorClass
										windRotation.style.width = "11px"
										windRotation.style.height = "11px"
										windRotation.style.display = "flex"


										const arrowIcon = document.createElement("img");
										arrowIcon.alt = 'Wind direction'
										arrowIcon.src = widget.dataset.path + "/resources/icons/ui-icons/arrow.svg"
										arrowIcon.style.width = "11px"
										arrowIcon.style.height = "11px"
										arrowIcon.style.transform = "rotate("+day.wind_deg+"deg)"

										windRotation.appendChild(arrowIcon)

										const wind = document.createElement("div");
										wind.classList = "WIYCW-forecast-wind WIYCW-col-1"

										wind.appendChild(windRotation)
										wind.innerHTML += formatSpeed(day.wind_speed, widget.dataset.units)
										row.appendChild(wind)
									}


									forecast.appendChild(row)


									
								}

								content.appendChild(forecast)
							}


						}, function(){
							console.log("Weather widget error")
						})
					}

					getWeatherData(widget.dataset.url+"?action="+widget.dataset.action+"&nonce="+widget.dataset.nonce+"&id="+widget.dataset.cityid, function(){
						getWeatherData("https://eltiempoen.com:9090/api/widget?id="+widget.dataset.cityid+"&s="+encodeURIComponent(window.location.hostname)+"&v="+widget.dataset.version+"&c=false", function(){
							console.log('Error getting direct call')
						})
					})


					
				})

})()