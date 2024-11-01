<?php
/**
 * Plugin Name: Weather Widget Pro
 * Plugin URI: https://weatherin.org
 * Description: Weather Widget Pro provides a complete weather forecast for any location around the world.
 * Version: 1.1.41
 * Author: El tiempo
 * Author URI: https://eltiempoen.com
 * Text Domain: weather-in-any-city-widget
 * Domain Path: /languages/
 * License: GPLv2 or later
**/



defined( 'ABSPATH' ) or die( 'ABSPATH not defined' );

define ('WIYCW_VERSION', '1.1.41');
define ('WIYCW_DEF_PLUGIN', 'weather-in-any-city-widget');
define ('WIYCW_DEF_BASEURL', plugins_url('', __FILE__));


add_action('init', 'WIYCW_textdomain');


function WIYCW_textdomain() {
    load_plugin_textdomain(WIYCW_DEF_PLUGIN, FALSE, plugin_basename(dirname(__FILE__)) . '/languages' );
}

function WIYCW_default_values($instance){
	$instance['title'] = !empty($instance['title']) ? $instance['title'] : "";
	$instance['cityID'] = !empty($instance['cityID']) ? $instance['cityID'] : 86168;
	$instance['location'] = !empty($instance['location']) ? $instance['location'] : "New York City, New York (United States)";
	$instance['city'] = !empty($instance['city']) ? $instance['city'] : "New York City";
	$instance['state'] = !empty($instance['state']) ? $instance['state'] : "New York";
	$instance['country'] = !empty($instance['country']) ? $instance['country'] : "United States";
	$instance['units'] = !empty($instance['units']) ? $instance['units'] : "c";
	$instance['today'] = !empty($instance['today']) ? $instance['today'] : "on";
	$instance['now_icon'] = !empty($instance['now_icon']) ? $instance['now_icon'] : "on";
	$instance['now_temp'] = !empty($instance['now_temp']) ? $instance['now_temp'] : "on";
	$instance['now_humidity'] = !empty($instance['now_humidity']) ? $instance['now_humidity'] : "on";
	$instance['now_pressure'] = !empty($instance['now_pressure']) ? $instance['now_pressure'] : "on";
	$instance['now_cloudiness'] = !empty($instance['now_cloudiness']) ? $instance['now_cloudiness'] : "on";
	$instance['now_wind'] = !empty($instance['now_wind']) ? $instance['now_wind'] : "on";
	$instance['now_sunrise'] = !empty($instance['now_sunrise']) ? $instance['now_sunrise'] : "off";
	$instance['days'] = !empty($instance['days']) ? $instance['days'] : 4;
	$instance['layout'] = !empty($instance['layout']) ? $instance['layout'] : "horizontal";
	$instance['wind'] = !empty($instance['wind']) ? $instance['wind'] : "off";
	$instance['rain'] = !empty($instance['rain']) ? $instance['rain'] : "off";
	$instance['rainChance'] = !empty($instance['rainChance']) ? $instance['rainChance'] : "on";
	$instance['icon'] = !empty($instance['icon']) ? $instance['icon'] : "on";
	$instance['temp'] = !empty($instance['temp']) ? $instance['temp'] : "on";
	$instance['backgroundColor'] = !empty($instance['backgroundColor']) ? $instance['backgroundColor'] : "#ffffff";
	$instance['fontSize'] = !empty($instance['fontSize']) ? $instance['fontSize'] : "15";
	$instance['borderColor'] = !empty($instance['borderColor']) ? $instance['borderColor'] : "#ffffff";
	$instance['textColor'] = !empty($instance['textColor']) ? $instance['textColor'] : "#000000";
	$instance['iconsColor'] = !empty($instance['iconsColor']) ? $instance['iconsColor'] : "dark";
	$instance['weatherIconsColor'] = !empty($instance['weatherIconsColor']) ? $instance['weatherIconsColor'] : "color";
	$instance['shadow'] = !empty($instance['shadow']) ? $instance['shadow'] : "off";
	$instance['credits'] = !empty($instance['credits']) ? $instance['credits'] : "on";
	$instance['url_en'] = !empty($instance['url_en']) ? $instance['url_en'] : "";
	$instance['url_es'] = !empty($instance['url_es']) ? $instance['url_es'] : "";
	$instance['time_format'] = !empty($instance['time_format']) ? $instance['time_format'] : "universal";
	$instance['url'] = admin_url('admin-ajax.php');
	$instance['action'] = "WIYCW_get_weather";
	return $instance;
}

function WIYCW_i18n(){
	$i18n = array('week_days' => 
				array(
					__('mon.', WIYCW_DEF_PLUGIN), 
					__('tue.', WIYCW_DEF_PLUGIN),
					__('wed.', WIYCW_DEF_PLUGIN),
					__('thu.', WIYCW_DEF_PLUGIN),
					__('fri.', WIYCW_DEF_PLUGIN),
					__('sat.', WIYCW_DEF_PLUGIN),
					__('sun.', WIYCW_DEF_PLUGIN)),
				'today' => __('today', WIYCW_DEF_PLUGIN),
				'tomorrow' => __('tmrw.', WIYCW_DEF_PLUGIN)
			);
	return $i18n;
}

class WIYCW_widget extends WP_Widget
{
    public function __construct()
    {
        $widget_options = array('classname' => 'WIYCW_widget', 'description' => __('Weather Widget Pro provides a complete weather forecast for any location around the world.', WIYCW_DEF_PLUGIN));
        parent::__construct('WIYCW_widget', __('Weather Widget Pro', WIYCW_DEF_PLUGIN), $widget_options);
    }

   
   	

    public function widget($args, $instance)
    {

    	$instance = WIYCW_default_values($instance);
    	$instance['isWidget'] = true;
    	echo WIYCW_public($instance);   
    }

    public function update($new_instance, $old_instance)
    {
        
        $instance = $old_instance;

        $instance['title'] = sanitize_text_field(strip_tags($new_instance['title']));
        $instance['cityID'] = sanitize_text_field(strip_tags($new_instance['cityID']));
        $instance['location'] = sanitize_text_field(strip_tags($new_instance['location'])); 
        $instance['city'] = sanitize_text_field(strip_tags($new_instance['city']));
        $instance['state'] = sanitize_text_field(strip_tags($new_instance['state']));
        $instance['country'] = sanitize_text_field(strip_tags($new_instance['country']));
        $instance['url_en'] = sanitize_text_field(strip_tags($new_instance['url_en']));
        $instance['url_es'] = sanitize_text_field(strip_tags($new_instance['url_es']));
        $instance['units'] = sanitize_text_field(strip_tags($new_instance['units']));
        $instance['today'] = sanitize_text_field($new_instance['today']);
        $instance['credits'] = sanitize_text_field($new_instance['credits']);
        $instance['now_icon'] = sanitize_text_field($new_instance['now_icon']);
        $instance['now_temp'] = sanitize_text_field($new_instance['now_temp']);
        $instance['now_humidity'] = sanitize_text_field($new_instance['now_humidity']);
        $instance['now_wind'] = sanitize_text_field($new_instance['now_wind']);
        $instance['now_sunrise'] = sanitize_text_field($new_instance['now_sunrise']);
        $instance['now_pressure'] = sanitize_text_field($new_instance['now_pressure']);
        $instance['now_cloudiness'] = sanitize_text_field($new_instance['now_cloudiness']);
        $instance['days'] = sanitize_text_field(strip_tags($new_instance['days']));
        $instance['layout'] = sanitize_text_field(strip_tags($new_instance['layout']));
        $instance['wind'] = sanitize_text_field($new_instance['wind']);
        $instance['rain'] = sanitize_text_field($new_instance['rain']);
        $instance['rainChance'] = sanitize_text_field($new_instance['rainChance']);
        $instance['icon'] = sanitize_text_field($new_instance['icon']);
        $instance['temp'] = sanitize_text_field($new_instance['temp']);
        $instance['backgroundColor'] = sanitize_hex_color(strip_tags($new_instance['backgroundColor']));
        $instance['textColor'] = sanitize_hex_color(strip_tags($new_instance['textColor']));
        $instance['borderColor'] = sanitize_hex_color(strip_tags($new_instance['borderColor']));
        $instance['iconsColor'] = sanitize_text_field(strip_tags($new_instance['iconsColor']));
        $instance['weatherIconsColor'] = sanitize_text_field(strip_tags($new_instance['weatherIconsColor']));
        $instance['fontSize'] = sanitize_text_field(strip_tags($new_instance['fontSize']));
        $instance['shadow'] = sanitize_text_field($new_instance['shadow']);
        $instance['time_format'] = sanitize_text_field($new_instance['time_format']);

        if ($new_instance['today'] != "on") {
            $instance['today'] = "off";
        }

        if ($new_instance['now_icon'] != "on") {
            $instance['now_icon'] = "off";
        }

         if ($new_instance['now_temp'] != "on") {
            $instance['now_temp'] = "off";
        }

        if ($new_instance['now_humidity'] != "on") {
            $instance['now_humidity'] = "off";
        }

        if ($new_instance['now_pressure'] != "on") {
            $instance['now_pressure'] = "off";
        }

        if ($new_instance['now_cloudiness'] != "on") {
            $instance['now_cloudiness'] = "off";
        }

        if ($new_instance['now_wind'] != "on") {
            $instance['now_wind'] = "off";
        }

        if ($new_instance['now_sunrise'] != "on") {
            $instance['now_sunrise'] = "off";
        }

        if ($new_instance['wind'] != "on") {
            $instance['wind'] = "off";
        }

        if ($new_instance['rain'] != "on") {
            $instance['rain'] = "off";
        }

        if ($new_instance['rainChance'] != "on") {
            $instance['rainChance'] = "off";
        }

        if ($new_instance['icon'] != "on") {
            $instance['icon'] = "off";
        }

        if ($new_instance['temp'] != "on") {
            $instance['temp'] = "off";
        }

        if ($new_instance['credits'] != "on") {
            $instance['credits'] = "off";
        }

        if ($new_instance['shadow'] != "on") {
            $instance['shadow'] = "off";
        }


        return $instance;
    }



     public function form($instance)
    {

    	$instance = WIYCW_default_values($instance);
    	$title = $instance['title'];
    	$cityID = $instance['cityID'];
    	$location = $instance['location'];
    	$city = $instance['city'];
    	$state = $instance['state'];
    	$country = $instance['country'];
    	$units = $instance['units'];
    	$today = $instance['today'];
    	$now_icon = $instance['now_icon'];
		$now_temp = $instance['now_temp'];
    	$now_humidity = $instance['now_humidity'];
    	$now_pressure = $instance['now_pressure'];
    	$now_cloudiness = $instance['now_cloudiness'];
    	$now_wind = $instance['now_wind'];
    	$now_sunrise = $instance['now_sunrise'];
    	$days = $instance['days'];
    	$layout = $instance['layout'];
    	$wind = $instance['wind'];
    	$rain = $instance['rain'];
    	$rainChance = $instance['rainChance'];
    	$icon = $instance['icon'];
    	$temp = $instance['temp'];
    	$backgroundColor = $instance['backgroundColor'];
    	$borderColor = $instance['borderColor'];
    	$textColor = $instance['textColor'];
    	$iconsColor = $instance['iconsColor'];
    	$weatherIconsColor = $instance['weatherIconsColor'];
    	$fontSize = $instance['fontSize'];
    	$shadow = $instance['shadow'];
    	$credits = $instance['credits'];
    	$url_es = $instance['url_es'];
    	$url_en = $instance['url_en'];
    	$time_format = $instance['time_format'];
    	$widgetID =  $this->id;


    	?>
    	
    	<div class="wrap">
	
			<div class="WIYCW-form WIYCW-admin" id="<?php echo esc_attr($widgetID); ?>"
			  data-search="<?php echo $this->get_field_id('search'); ?>"
			  data-location="<?php echo $this->get_field_id('location'); ?>"
			  data-city="<?php echo $this->get_field_id('city'); ?>"
			  data-cityid="<?php echo $this->get_field_id('cityID'); ?>"
			  data-state="<?php echo $this->get_field_id('state'); ?>"
			  data-country="<?php echo $this->get_field_id('country'); ?>"
			  data-url_en="<?php echo $this->get_field_id('url_en'); ?>"
			  data-url_es="<?php echo $this->get_field_id('url_es'); ?>"
			  data-init="false"
			 >
				
				<div class="form-section">
					<h3><?php echo __('General', WIYCW_DEF_PLUGIN)?></h3>

					
					<p class="widefat autoComplete_wrapper">
						<label for="<?php echo $this->get_field_id('location'); ?>"><?php echo __('City', WIYCW_DEF_PLUGIN)?></label>	

						<input class="widefat WIYCW-city" type="search"
						for="<?php echo $this->get_field_id('search'); ?>" 
						id="<?php echo $this->get_field_id('search'); ?>"
						name="<?php echo $this->get_field_name('search'); ?>"
						value="<?php echo esc_attr($location); ?>"></input>

						<input type="hidden" 
						for="<?php echo $this->get_field_id('location'); ?>" 
						id="<?php echo $this->get_field_id('location'); ?>"
						name="<?php echo $this->get_field_name('location'); ?>"
						value="<?php echo esc_attr($location); ?>"></input>

						<input type="hidden" 
						for="<?php echo $this->get_field_id('city'); ?>" 
						id="<?php echo $this->get_field_id('city'); ?>"
						name="<?php echo $this->get_field_name('city'); ?>"
						value="<?php echo esc_attr($city); ?>"></input>

						<input type="hidden" 
						for="<?php echo $this->get_field_id('cityID'); ?>" 
						id="<?php echo $this->get_field_id('cityID'); ?>"
						name="<?php echo $this->get_field_name('cityID'); ?>"
						value="<?php echo esc_attr($cityID); ?>"></input>

						<input type="hidden" 
						for="<?php echo $this->get_field_id('state'); ?>" 
						id="<?php echo $this->get_field_id('state'); ?>"
						name="<?php echo $this->get_field_name('state'); ?>"
						value="<?php echo esc_attr($state); ?>"></input>

						<input type="hidden" 
						for="<?php echo $this->get_field_id('country'); ?>" 
						id="<?php echo $this->get_field_id('country'); ?>"
						name="<?php echo $this->get_field_name('country'); ?>"
						value="<?php echo esc_attr($country); ?>"></input>

						<input type="hidden" 
						for="<?php echo $this->get_field_id('url_en'); ?>" 
						id="<?php echo $this->get_field_id('url_en'); ?>"
						name="<?php echo $this->get_field_name('url_en'); ?>"
						value="<?php echo esc_attr($url_en); ?>"></input>

						<input type="hidden" 
						for="<?php echo $this->get_field_id('url_es'); ?>" 
						id="<?php echo $this->get_field_id('url_es'); ?>"
						name="<?php echo $this->get_field_name('url_es'); ?>"
						value="<?php echo esc_attr($url_es); ?>"></input>

					</p>

					<p class="form-line">
						<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title', WIYCW_DEF_PLUGIN)?> (<?php echo __('optional', WIYCW_DEF_PLUGIN)?>)</label>		
						<input class="widefat" type="text"
						for="<?php echo $this->get_field_id('title'); ?>" 
						id="<?php echo $this->get_field_id('title'); ?>"
						name="<?php echo $this->get_field_name('title'); ?>"
						value="<?php echo esc_attr($title); ?>"></input>
						<p style="font-size: 0.8rem;font-style: italic;"><?php echo __('Title will overwrite the city name', WIYCW_DEF_PLUGIN)?></p>
					</p>
					

					<p class="form-line">

						<label for="<?php echo $this->get_field_id('units'); ?>"><?php echo __('Units', WIYCW_DEF_PLUGIN)?></label>		

						<select class="widefat" name="<?php echo $this->get_field_name('units'); ?>" id="<?php echo $this->get_field_id('units'); ?>">
	                        <option value="c" <?php if ($units == "c") {
	                            echo 'selected';
	                        } ?>><?php echo __('Celsius', WIYCW_DEF_PLUGIN)?>
	                        </option>
	                        <option value="f" <?php if ($units == "f") {
	                            echo 'selected';
	                        } ?>><?php echo __('Fahrenheit', WIYCW_DEF_PLUGIN)?>
	                        </option>
		                </select>
					</p>
				</div>
				
				<div class="form-section">
					<h2><?php echo __('Current conditions', WIYCW_DEF_PLUGIN)?></h2>

					<p class="form-line">
						<input class="checkbox" type="checkbox" 
							<?php if ($today == "on") {
	                            echo 'checked';
	                        }; ?>
							for="<?php echo $this->get_field_id('today'); ?>" 
							id="<?php echo $this->get_field_id('today'); ?>"
							name="<?php echo $this->get_field_name('today'); ?>"
							></input>
						<label for="<?php echo $this->get_field_id('today'); ?>"><?php echo __('Show current conditions', WIYCW_DEF_PLUGIN)?></label>	
					</p>

					<p class="form-line">
						<input class="checkbox" type="checkbox" 
							<?php if ($now_icon == "on") {
	                            echo 'checked';
	                        }; ?>
							for="<?php echo $this->get_field_id('now_icon'); ?>" 
							id="<?php echo $this->get_field_id('now_icon'); ?>"
							name="<?php echo $this->get_field_name('now_icon'); ?>"
							></input>
						<label for="<?php echo $this->get_field_id('now_icon'); ?>"><?php echo __('Icon', WIYCW_DEF_PLUGIN)?></label>	
					</p>

					<p class="form-line">
						<input class="checkbox" type="checkbox" 
							<?php if ($now_temp == "on") {
	                            echo 'checked';
	                        }; ?>
							for="<?php echo $this->get_field_id('now_temp'); ?>" 
							id="<?php echo $this->get_field_id('now_temp'); ?>"
							name="<?php echo $this->get_field_name('now_temp'); ?>"
							></input>
						<label for="<?php echo $this->get_field_id('now_temp'); ?>"><?php echo __('Temperature', WIYCW_DEF_PLUGIN)?></label>	
					</p>

					<p class="form-line">
						<input class="checkbox" type="checkbox" 
							<?php if ($now_humidity == "on") {
	                            echo 'checked';
	                        }; ?>
							for="<?php echo $this->get_field_id('now_humidity'); ?>" 
							id="<?php echo $this->get_field_id('now_humidity'); ?>"
							name="<?php echo $this->get_field_name('now_humidity'); ?>"
							></input>
						<label for="<?php echo $this->get_field_id('now_humidity'); ?>"><?php echo __('Humidity', WIYCW_DEF_PLUGIN)?></label>	
					</p>

					<p class="form-line">
						<input class="checkbox" type="checkbox" 
							<?php if ($now_wind == "on") {
	                            echo 'checked';
	                        }; ?>
							for="<?php echo $this->get_field_id('now_wind'); ?>" 
							id="<?php echo $this->get_field_id('now_wind'); ?>"
							name="<?php echo $this->get_field_name('now_wind'); ?>"
							></input>
						<label for="<?php echo $this->get_field_id('now_wind'); ?>"><?php echo __('Wind', WIYCW_DEF_PLUGIN)?></label>	
					</p>

					<p class="form-line">
						<input class="checkbox" type="checkbox" 
							<?php if ($now_pressure == "on") {
	                            echo 'checked';
	                        }; ?>
							for="<?php echo $this->get_field_id('now_pressure'); ?>" 
							id="<?php echo $this->get_field_id('now_pressure'); ?>"
							name="<?php echo $this->get_field_name('now_pressure'); ?>"
							></input>
						<label for="<?php echo $this->get_field_id('now_pressure'); ?>"><?php echo __('Pressure', WIYCW_DEF_PLUGIN)?></label>	
					</p>

					<p class="form-line">
						<input class="checkbox" type="checkbox" 
							<?php if ($now_cloudiness == "on") {
	                            echo 'checked';
	                        }; ?>
							for="<?php echo $this->get_field_id('now_cloudiness'); ?>" 
							id="<?php echo $this->get_field_id('now_cloudiness'); ?>"
							name="<?php echo $this->get_field_name('now_cloudiness'); ?>"
							></input>
						<label for="<?php echo $this->get_field_id('now_cloudiness'); ?>"><?php echo __('Cloudiness', WIYCW_DEF_PLUGIN)?></label>	
					</p>



					<p class="form-line">
						<input class="checkbox" type="checkbox" 
							<?php if ($now_sunrise == "on") {
	                            echo 'checked';
	                        }; ?>
							for="<?php echo $this->get_field_id('now_sunrise'); ?>" 
							id="<?php echo $this->get_field_id('now_sunrise'); ?>"
							name="<?php echo $this->get_field_name('now_sunrise'); ?>"
							></input>
						<label for="<?php echo $this->get_field_id('now_sunrise'); ?>"><?php echo __('Sunrise / sunset', WIYCW_DEF_PLUGIN)?></label>	
					</p>

					<p class="form-line">
						<label for="<?php echo $this->get_field_id('time_format'); ?>"><?php echo __('Time format', WIYCW_DEF_PLUGIN)?></label>		

						<select class="widefat" name="<?php echo $this->get_field_name('time_format'); ?>" id="<?php echo $this->get_field_id('time_format'); ?>">
	                        <option value="universal" <?php if ($time_format == "universal") {
	                            echo 'selected';
	                        } ?>><?php echo __('24 hours', WIYCW_DEF_PLUGIN)?>
	                        </option>
	                        <option value="standard" <?php if ($time_format == "standard") {
	                            echo 'selected';
	                        } ?>><?php echo __('12 hours', WIYCW_DEF_PLUGIN)?>
	                        </option>
		                </select>
					</p>



				</div>
				
				<div class="form-section">
					<h2><?php echo __('Forecast', WIYCW_DEF_PLUGIN)?></h2>
					<p class="form-line">
						<label for="<?php echo $this->get_field_id('days'); ?>"><?php echo __('Days', WIYCW_DEF_PLUGIN)?></label>
						<select class="widefat" name="<?php echo $this->get_field_name('days'); ?>" id="<?php echo $this->get_field_id('days'); ?>">

							 		<option value="off" <?php if ($days == "off") {
			                            echo 'selected';
			                        } ?>><?php echo __('Disable forecast',WIYCW_DEF_PLUGIN); ?>
			                        </option>
			                        <option value="1" <?php if ($days == 1) {
			                            echo 'selected';
			                        } ?>><?php echo __('1 day',WIYCW_DEF_PLUGIN); ?>
			                        </option>
			                        <option value="2" <?php if ($days == 2) {
			                            echo 'selected';
			                        } ?>><?php echo __('2 days',WIYCW_DEF_PLUGIN); ?>
			                        </option>
			                        <option value="3" <?php if ($days == 3) {
			                            echo 'selected';
			                        } ?>><?php echo __('3 days',WIYCW_DEF_PLUGIN); ?>
			                        </option>
			                        <option value="4" <?php if ($days == 4) {
			                            echo 'selected';
			                        } ?>><?php echo __('4 days',WIYCW_DEF_PLUGIN); ?>
			                        </option>
			                        <option value="5" <?php if ($days == 5) {
			                            echo 'selected';
			                        } ?>><?php echo __('5 days',WIYCW_DEF_PLUGIN); ?>
			                        </option>
			                        <option value="6" <?php if ($days == 6) {
			                            echo 'selected';
			                        } ?>><?php echo __('6 days',WIYCW_DEF_PLUGIN); ?>
			                        </option>
			                        <option value="7" <?php if ($days == 7) {
			                            echo 'selected';
			                        } ?>><?php echo __('7 days',WIYCW_DEF_PLUGIN); ?>
			                        </option>
			                        <option value="8" <?php if ($days == 8) {
			                            echo 'selected';
			                        } ?>><?php echo __('8 days',WIYCW_DEF_PLUGIN); ?>
			                        </option>
			                        <option value="9" <?php if ($days == 9) {
			                            echo 'selected';
			                        } ?>><?php echo __('9 days',WIYCW_DEF_PLUGIN); ?>
			                        </option>
			                        <option value="10" <?php if ($days == 10) {
			                            echo 'selected';
			                        } ?>><?php echo __('10 days',WIYCW_DEF_PLUGIN); ?>
			                        </option>
			                        <option value="11" <?php if ($days == 11) {
			                            echo 'selected';
			                        } ?>><?php echo __('11 days',WIYCW_DEF_PLUGIN); ?>
			                        </option>
			                        <option value="12" <?php if ($days == 12) {
			                            echo 'selected';
			                        } ?>><?php echo __('12 days',WIYCW_DEF_PLUGIN); ?>
			                        </option>
			                        <option value="13" <?php if ($days == 13) {
			                            echo 'selected';
			                        } ?>><?php echo __('13 days',WIYCW_DEF_PLUGIN); ?>
			                        </option>
			                        <option value="14" <?php if ($days == 14) {
			                            echo 'selected';
			                        } ?>><?php echo __('14 days',WIYCW_DEF_PLUGIN); ?>
			                        </option>
			                        <option value="15" <?php if ($days == 15) {
			                            echo 'selected';
			                        } ?>><?php echo __('15 days',WIYCW_DEF_PLUGIN); ?>
			                        </option>
			                    </select>	
					</p>

					
					<p class="form-line">
						<label for="<?php echo $this->get_field_id('layout'); ?>"><?php echo __('Layout', WIYCW_DEF_PLUGIN)?></label>		

						<select class="widefat" name="<?php echo $this->get_field_name('layout'); ?>" id="<?php echo $this->get_field_id('layout'); ?>">
	                        <option value="vertical" <?php if ($layout == "vertical") {
	                            echo 'selected';
	                        } ?>><?php echo __('Vertical', WIYCW_DEF_PLUGIN)?>
	                        </option>
	                        <option value="horizontal" <?php if ($layout == "horizontal") {
	                            echo 'selected';
	                        } ?>><?php echo __('Horizontal', WIYCW_DEF_PLUGIN)?>
	                        </option>
		                </select>
					</p>


					<p class="form-line">
						<input type="checkbox" 
							<?php if ($temp == "on") {
                                echo 'checked';
                            }; ?>
							for="<?php echo $this->get_field_id('temp'); ?>" 
							id="<?php echo $this->get_field_id('temp'); ?>"
							name="<?php echo $this->get_field_name('temp'); ?>"
							></input>
						<label for="<?php echo $this->get_field_id('temp'); ?>"><?php echo __('Temperature', WIYCW_DEF_PLUGIN)?></label>	
					</p>

					<p class="form-line">
						
						<input type="checkbox" 
							<?php if ($icon == "on") {
                                echo 'checked';
                            }; ?>
							for="<?php echo $this->get_field_id('icon'); ?>" 
							id="<?php echo $this->get_field_id('icon'); ?>"
							name="<?php echo $this->get_field_name('icon'); ?>"
							></input>
						<label for="<?php echo $this->get_field_id('icon'); ?>"><?php echo __('Icon', WIYCW_DEF_PLUGIN)?></label>
					</p>

					<p class="form-line">
						<input type="checkbox" 
						<?php if ($wind == "on") {
                            echo 'checked';
                        }; ?>
						for="<?php echo $this->get_field_id('wind'); ?>" 
						id="<?php echo $this->get_field_id('wind'); ?>"
						name="<?php echo $this->get_field_name('wind'); ?>"
						></input>
						<label for="<?php echo $this->get_field_id('temp'); ?>"><?php echo __('Wind', WIYCW_DEF_PLUGIN)?></label>
					</p>


					<p class="form-line">
						<input class="WIYCW-checkbox" type="checkbox" 
							<?php if ($rainChance == "on") {
                                echo 'checked';
                            }; ?>
							for="<?php echo $this->get_field_id('rainChance'); ?>" 
							id="<?php echo $this->get_field_id('rainChance'); ?>"
							name="<?php echo $this->get_field_name('rainChance'); ?>"
							></input>
						<label for="<?php echo $this->get_field_id('rainChance'); ?>"><?php echo __('Rain probability', WIYCW_DEF_PLUGIN)?></label>
					</p>
				</div>


				<div class="form-section">
					<h2><?php echo __('Look and feel', WIYCW_DEF_PLUGIN)?></h2>

					<p class="form-line">
						<label for="<?php echo $this->get_field_id('fontSize'); ?>"><?php echo __('Font size', WIYCW_DEF_PLUGIN)?></label>
						 <input type="number" id="<?php echo $this->get_field_id('fontSize'); ?>"
		                        name="<?php echo $this->get_field_name('fontSize'); ?>"
		                        value="<?php echo esc_attr($fontSize); ?>"/>
					</p>

					<p class="form-line">

						<label for="<?php echo $this->get_field_id('weatherIconsColor'); ?>"><?php echo __('Weather icons color', WIYCW_DEF_PLUGIN)?></label>		

						<select class="widefat" name="<?php echo $this->get_field_name('weatherIconsColor'); ?>" id="<?php echo $this->get_field_id('weatherIconsColor'); ?>">
							 <option value="color" <?php if ($weatherIconsColor == "color") {
	                            echo 'selected';
	                        } ?>><?php echo __('Color', WIYCW_DEF_PLUGIN)?>
	                        </option>
	                        <option value="light" <?php if ($weatherIconsColor == "light") {
	                            echo 'selected';
	                        } ?>><?php echo __('Light', WIYCW_DEF_PLUGIN)?>
	                        </option>
	                        <option value="dark" <?php if ($weatherIconsColor == "dark") {
	                            echo 'selected';
	                        } ?>><?php echo __('Dark', WIYCW_DEF_PLUGIN)?>
	                        </option>
		                </select>
					</p>

				

					<p class="form-line">

						<label for="<?php echo $this->get_field_id('iconsColor'); ?>"><?php echo __('UI icons color', WIYCW_DEF_PLUGIN)?></label>		

						<select class="widefat" name="<?php echo $this->get_field_name('iconsColor'); ?>" id="<?php echo $this->get_field_id('iconsColor'); ?>">
	                        <option value="light" <?php if ($iconsColor == "light") {
	                            echo 'selected';
	                        } ?>><?php echo __('Light', WIYCW_DEF_PLUGIN)?>
	                        </option>
	                        <option value="dark" <?php if ($iconsColor == "dark") {
	                            echo 'selected';
	                        } ?>><?php echo __('Dark', WIYCW_DEF_PLUGIN)?>
	                        </option>
		                </select>
					</p>

					

					<p class="form-line">
						<label for="<?php echo $this->get_field_id('textColor'); ?>"><?php echo __('Font color', WIYCW_DEF_PLUGIN)?></label>
						 <input type="color" id="<?php echo $this->get_field_id('textColor'); ?>"
		                        name="<?php echo $this->get_field_name('textColor'); ?>"
		                        value="<?php echo esc_attr($textColor); ?>"/>
					</p>

					
					<p class="form-line">
						<label for="<?php echo $this->get_field_id('backgroundColor'); ?>"><?php echo __('Background color', WIYCW_DEF_PLUGIN)?></label>
						 <input type="color" id="<?php echo $this->get_field_id('backgroundColor'); ?>"
		                        name="<?php echo $this->get_field_name('backgroundColor'); ?>"
		                        value="<?php echo esc_attr($backgroundColor); ?>"/>
					</p>


					<p class="form-line">
						<label for="<?php echo $this->get_field_id('borderColor'); ?>"><?php echo __('Border color', WIYCW_DEF_PLUGIN)?></label>
						 <input type="color" id="<?php echo $this->get_field_id('borderColor'); ?>"
		                        name="<?php echo $this->get_field_name('borderColor'); ?>"
		                        value="<?php echo esc_attr($borderColor); ?>"/>
					</p>

					<p class="form-line">
						<input class="checkbox" type="checkbox" 
							<?php if ($shadow == "on") {
	                            echo 'checked';
	                        }; ?>
							for="<?php echo $this->get_field_id('shadow'); ?>" 
							id="<?php echo $this->get_field_id('shadow'); ?>"
							name="<?php echo $this->get_field_name('shadow'); ?>"
							></input>
						<label for="<?php echo $this->get_field_id('shadow'); ?>"><?php echo __('Shadow', WIYCW_DEF_PLUGIN)?></label>	
					</p>

				
				</div>

				<div class="form-section">
					<h2><?php echo __('Shortcode', WIYCW_DEF_PLUGIN)?></h2>

					<p class="form-line" style="font-size:0.9rem">
						[weather_pro cityid="<span id="<?php echo $this->get_field_id('cityID')."-prev"; ?>"><?php echo esc_attr($cityID); ?></span>" title="<span id="<?php echo $this->get_field_id('title')."-prev"; ?>"><?php echo esc_attr($title); ?></span>" city="<span id="<?php echo $this->get_field_id('city')."-prev"; ?>"><?php echo esc_attr($city); ?></span>" units="<span id="<?php echo $this->get_field_id('units')."-prev"; ?>"><?php echo esc_attr($units); ?></span>" today="<span id="<?php echo $this->get_field_id('today')."-prev"; ?>"><?php echo esc_attr($today); ?></span>" now_icon="<span id="<?php echo $this->get_field_id('now_icon')."-prev"; ?>"><?php echo esc_attr($now_icon); ?></span>" now_temp="<span id="<?php echo $this->get_field_id('now_temp')."-prev"; ?>"><?php echo esc_attr($now_temp); ?></span>" now_humidity="<span id="<?php echo $this->get_field_id('now_humidity')."-prev"; ?>"><?php echo esc_attr($now_humidity); ?></span>" now_pressure="<span id="<?php echo $this->get_field_id('now_pressure')."-prev"; ?>"><?php echo esc_attr($now_pressure); ?></span>" now_cloudiness="<span id="<?php echo $this->get_field_id('now_cloudiness')."-prev"; ?>"><?php echo esc_attr($now_cloudiness); ?></span>" now_wind="<span id="<?php echo $this->get_field_id('now_wind')."-prev"; ?>"><?php echo esc_attr($now_wind); ?></span>" now_sunrise="<span id="<?php echo $this->get_field_id('now_sunrise')."-prev"; ?>"><?php echo esc_attr($now_sunrise); ?></span>" time_format="<span id="<?php echo $this->get_field_id('time_format')."-prev"; ?>"><?php echo esc_attr($time_format); ?></span>" days="<span id="<?php echo $this->get_field_id('days')."-prev"; ?>"><?php echo esc_attr($days); ?></span>" layout="<span id="<?php echo $this->get_field_id('layout')."-prev"; ?>"><?php echo esc_attr($layout); ?></span>" wind="<span id="<?php echo $this->get_field_id('wind')."-prev"; ?>"><?php echo esc_attr($wind); ?></span>" rain="<span id="<?php echo $this->get_field_id('rain')."-prev"; ?>"><?php echo esc_attr($rain); ?></span>" rainchance="<span id="<?php echo $this->get_field_id('rainChance')."-prev"; ?>"><?php echo esc_attr($rainChance); ?></span>" icon="<span id="<?php echo $this->get_field_id('icon')."-prev"; ?>"><?php echo esc_attr($icon); ?></span>" temp="<span id="<?php echo $this->get_field_id('temp')."-prev"; ?>"><?php echo esc_attr($temp); ?></span>" backgroundcolor="<span id="<?php echo $this->get_field_id('backgroundColor')."-prev"; ?>"><?php echo esc_attr($backgroundColor); ?></span>" bordercolor="<span id="<?php echo $this->get_field_id('borderColor')."-prev"; ?>"><?php echo esc_attr($borderColor); ?></span>" textcolor="<span id="<?php echo $this->get_field_id('textColor')."-prev"; ?>"><?php echo esc_attr($textColor); ?></span>" iconscolor="<span id="<?php echo $this->get_field_id('iconsColor')."-prev"; ?>"><?php echo esc_attr($iconsColor); ?></span>" weathericonscolor="<span id="<?php echo $this->get_field_id('weatherIconsColor')."-prev"; ?>"><?php echo esc_attr($weatherIconsColor); ?></span>" fontsize="<span id="<?php echo $this->get_field_id('fontSize')."-prev"; ?>"><?php echo esc_attr($fontSize); ?></span>" shadow="<span id="<?php echo $this->get_field_id('shadow')."-prev"; ?>"><?php echo esc_attr($shadow); ?></span>"]
					</p>

				
				</div>		
			</div>
			
		</div>
	
    	<?php
    }

}


function WIYCW_widget()
{
    register_widget('WIYCW_widget');
}

add_action('widgets_init', 'WIYCW_widget');


function WIYCW_admin_scripts( $hook ) {
    wp_enqueue_style('WIYCW-autoComplete-style', plugin_dir_url(__FILE__). 'resources/css/WIYCW-autoComplete.css', array(), WIYCW_VERSION);
    wp_register_script('WIYCW-admin', plugin_dir_url(__FILE__). 'resources/js/WIYCW-admin-widget.js', array('jquery'), WIYCW_VERSION, true);
   	wp_register_script('WIYCW-autoComplete', plugin_dir_url(__FILE__). 'resources/js/WIYCW-autoComplete.min.js', array(), WIYCW_VERSION, true);
   	wp_enqueue_script('WIYCW-admin');
   	wp_enqueue_script('WIYCW-autoComplete');
}

add_action('admin_enqueue_scripts', 'WIYCW_admin_scripts');


function WIYCW_public($instance){
	wp_enqueue_style('WIYCW-style', plugin_dir_url(__FILE__). 'resources/css/WIYCW-style.css', array(), WIYCW_VERSION);
   	wp_register_script('WIYCW-widget', plugin_dir_url(__FILE__). 'resources/js/WIYCW-widget.js', array(), WIYCW_VERSION);
   	wp_enqueue_script('WIYCW-widget');

   	$i18n = WIYCW_i18n();
   	$lang = get_bloginfo("language");

   	$credits = "";
   	if(!empty($instance['url_en']) && !empty($instance['url_es'])){
   		if($lang == "es" || $lang == "es_ES" || $lang == "es_CL"){
	   		$credits = "<a class='WIYCW-credit' style='text-decoration: none; color:".esc_attr($instance['textColor'])."' href='https://eltiempoen.com/".esc_attr($instance['url_es'])."'>El tiempo en ".esc_attr($instance['city'])."</a>";
	   	}else if(str_starts_with($lang, 'es')){
			$credits = "<a class='WIYCW-credit' style='text-decoration: none; color:".esc_attr($instance['textColor'])."' href='https://eltiempoen.com/".esc_attr($instance['url_es'])."'>Clima en ".esc_attr($instance['city'])."</a>";
	   	}else{
	   		$credits = "<a class='WIYCW-credit' style='text-decoration: none; color:".esc_attr($instance['textColor'])."' href='https://weatherin.org/".esc_attr($instance['url_en'])."'>".esc_attr($instance['city'])." weather</a>";
	   	}
   	}else{
	   	if($lang == "es" || $lang == "es_ES" || $lang == "es_CL"){
	   		$credits = "<a class='WIYCW-credit' style='text-decoration: none; color:".esc_attr($instance['textColor'])."' href='https://eltiempoen.com'>El tiempo</a> por eltiempoen.com";
	   	}else if(str_starts_with($lang, 'es')){
	   		$credits = "<a class='WIYCW-credit' style='text-decoration: none; color:".esc_attr($instance['textColor'])."' href='https://eltiempoen.com'>Clima</a> por eltiempoen.com";
	   	}else{
	   		$credits = "<a class='WIYCW-credit' style='text-decoration: none; color:".esc_attr($instance['textColor'])."' href='https://weatherin.org'>Weather</a> by weatherin.org";
	   	}
   	}

   	if(!empty($instance['title'])){
   		$title = $instance['title'];
   	}else{
   		$title = $instance['city'];
   	}

   	$iconColorClass = $instance['iconsColor'] == 'dark' ? "WIYCW-icon-dark" : "WIYCW-icon-light";
   	$shadowClass = $instance['shadow'] == 'on' ? "WIYCW-text-shadow" : "";
   	$baseURL = esc_url(WIYCW_DEF_BASEURL);
    $output = "<script>";
    $output .= "var WIYCW_i18n = ".json_encode($i18n)."";
    $output .= "</script>";
    if($instance['isWidget'] == true){
 		$output .= "<div class='widget'>";
    }else{
    	$output .= "<div class='WIYCW-shortcode'>";
    }
	$output .= "<div class='WIYCW-wrapper ".esc_attr($shadowClass)."'";
	$output .= " style='font-size:".esc_attr($instance['fontSize'])."px;color:".esc_attr($instance['textColor'])."; background-color:".esc_attr($instance['backgroundColor'])."; border: solid 1px ".esc_attr($instance['borderColor'])."'";
	$output .= " data-version='".esc_attr(WIYCW_VERSION)."'";
	$output .= " data-path='".esc_url(WIYCW_DEF_BASEURL)."'";
	$output .= " data-lang='".esc_attr($lang)."'";
	$output .= " data-cityid='".esc_attr($instance['cityID'])."'";
	$output .= " data-city='".esc_attr($instance['city'])."'";
	$output .= " data-units='".esc_attr($instance['units'])."'";
	$output .= " data-today='".esc_attr($instance['today'])."'";
	$output .= " data-nowicon='".esc_attr($instance['now_icon'])."'";
	$output .= " data-nowtemp='".esc_attr($instance['now_temp'])."'";
	$output .= " data-nowhumidity='".esc_attr($instance['now_humidity'])."'";
	$output .= " data-nowwind='".esc_attr($instance['now_wind'])."'";
	$output .= " data-nowsunrise='".esc_attr($instance['now_sunrise'])."'";
	$output .= " data-timeformat='".esc_attr($instance['time_format'])."'";
	$output .= " data-nowpressure='".esc_attr($instance['now_pressure'])."'";
	$output .= " data-nowcloudiness='".esc_attr($instance['now_cloudiness'])."'";
	$output .= " data-days='".esc_attr($instance['days'])."'";
	$output .= " data-layout='".esc_attr($instance['layout'])."'";
	$output .= " data-wind='".esc_attr($instance['wind'])."'";
	$output .= " data-rain='".esc_attr($instance['rain'])."'";
	$output .= " data-rainchance='".esc_attr($instance['rainChance'])."'";
	$output .= " data-forecasticon='".esc_attr($instance['icon'])."'";
	$output .= " data-temp='".esc_attr($instance['temp'])."'";
	$output .= " data-weathericonscolor='".esc_attr($instance['weatherIconsColor'])."'";
	$output .= " data-iconscolor='".esc_attr($instance['iconsColor'])."'";
	$output .= " data-backgroundcolor='".esc_attr($instance['backgroundColor'])."'";
	$output .= " data-textcolor='".esc_attr($instance['textColor'])."'";
	$output .= " data-shadow='".esc_attr($instance['shadow'])."'";
	$output .= " data-url='".esc_attr($instance['url'])."'";
	$output .= " data-version='".esc_attr(WIYCW_VERSION)."'";
	$output .= " data-action='".esc_attr($instance['action'])."'";
	$output .= " data-nonce='".esc_attr(wp_create_nonce(WIYCW_VERSION))."'";
	$output .= " data-bordercolor='".esc_attr($instance['borderColor'])."'";
	$output .=  ">";
	$output .= "<div class='WIYCW-header'>";
	$output .=  esc_html($title);
	$output .=  "</div>";
	$output .= "<div class='WIYCW-content'>";


	if($instance['today'] == "on"){
		$output .= "<div class='WIYCW-now'>";

		$output .= "<div class='WIYCW-now-row1'>";
		if($instance['now_icon'] == "on"){
			$output .=  "<div class='WIYCW-now-icon'><img alt='Current weather' style='visibility:hidden; width: 70px; height: 70px;'></div>";
		}

		if($instance['now_temp'] == "on"){
			$output .=  "<div class='WIYCW-now-temp'>-º</div>";
		}
		$output .=  "</div>";

		$output .=  "<div class='WIYCW-now-row-info'>";
		if($instance['now_sunrise'] == "on"){
			$output .= "<div class='WIYCW-now-row-info-col'><img alt='Sunrise' class='WIYCW-item-icon ".esc_attr($iconColorClass)."' src='".esc_attr($baseURL)."/resources/icons/ui-icons/sunrise.svg' style='width: 11px; height: 11px;'>-</div>";
			$output .= "<div class='WIYCW-now-row-info-col'><img alt='Sunset' class='WIYCW-item-icon ".esc_attr($iconColorClass)."' src='".esc_attr($baseURL)."/resources/icons/ui-icons/sunset.svg' style='width: 11px; height: 11px;'>-</div>";
		}
		if($instance['now_humidity'] == "on"){
			$output .= "<div class='WIYCW-now-row-info-col'><img alt='Humidity'class='WIYCW-item-icon ".esc_attr($iconColorClass)."' src='".esc_attr($baseURL)."/resources/icons/ui-icons/dew-point.svg' style='width: 11px; height: 11px;'>-</div>";
		}

		if($instance['now_wind'] == "on"){
			$output .= "<div class='WIYCW-now-row-info-col'><img alt='Wind direction' class='WIYCW-item-icon ".esc_attr($iconColorClass)."' src='".esc_attr($baseURL)."/resources/icons/ui-icons/arrow.svg' style='width: 11px; height: 11px;transform: rotate(0deg)'>-</div>";
		}

		if($instance['now_pressure'] == "on"){
			$output .= "<div class='WIYCW-now-row-info-col'><img alt='Pressure' class='WIYCW-item-icon ".esc_attr($iconColorClass)."' src='".esc_attr($baseURL)."/resources/icons/ui-icons/pressure.svg' style='width: 11px; height: 11px;'>-</div>";
		}

		if($instance['now_cloudiness'] == "on"){
			$output .= "<div class='WIYCW-now-row-info-col'><img alt='Cloudiness'class='WIYCW-item-icon ".esc_attr($iconColorClass)."' src='".esc_attr($baseURL)."/resources/icons/ui-icons/clouds.svg' style='width: 11px; height: 11px;'>-</div>";
		}

		$output .=  "</div>";
		$output .=  "</div>";
	}

	if($instance['days'] != "off"){
		$output .= "<div class='WIYCW-forecast WIYCW-forecast-".esc_attr($instance['layout'])."'>";

		for($WIYCWcounter=0; $WIYCWcounter < $instance['days']; $WIYCWcounter++){
			$output .= "<div class='WIYCW-forecast-row'>";
			$output .= "<div class='WIYCW-forecast-date WIYCW-col-1'>-</div>";

			if($instance['temp'] == "on"){
				$output .= "<div class='WIYCW-forecast-temp WIYCW-col-1'>-</div>";
			}
			if($instance['icon'] == "on"){
				$output .= "<div class='WIYCW-forecast-icon WIYCW-col-1'><img alt='Forecast' style='visibility:hidden; width: 30px; height: 30px;'></div>";
			}
			if($instance['rainChance'] == "on"){
				$output .= "<div class='WIYCW-forecast-pop WIYCW-col-1'><img alt='Rain chance' class='WIYCW-item-icon ".esc_attr($iconColorClass)."' src='".esc_attr($baseURL)."/resources/icons/ui-icons/umbrella.svg' style='width: 11px; height: 11px;'>-</div>";
			}
			if($instance['wind'] == "on"){
				$output .= "<div class='WIYCW-forecast-pop WIYCW-col-1'><img alt='Wind direction' class='WIYCW-item-icon ".esc_attr($iconColorClass)."' src='".esc_attr($baseURL)."/resources/icons/ui-icons/arrow.svg' style='width: 11px; height: 11px;transform: rotate(0deg);'>-</div>";
			}

			$output .=  "</div>";
		}

		$output .=  "</div>";
	}	


	$output .= "</div>";
	$output .= "<div class='WIYCW-footer'>";
	$output .=  $credits;
	$output .=  "</div>";
	$output .= "</div>";
	$output .= "</div>";
	return $output;
}

function WIYCW_shortcode($atts = []	){
	$atts['cityID'] = empty($atts['cityid']) ? "" : $atts['cityid'];
	$atts['backgroundColor'] = empty($atts['backgroundcolor']) ? "" : $atts['backgroundcolor'];
	$atts['fontSize'] = empty($atts['fontsize']) ? "" : $atts['fontsize'];
	$atts['textColor'] = empty($atts['textcolor']) ? "" : $atts['textcolor'];
	$atts['borderColor'] = empty($atts['bordercolor']) ? "" : $atts['bordercolor'];
	$atts['rainChance'] = empty($atts['rainchance']) ? "" : $atts['rainchance'];
	$atts['iconsColor'] = empty($atts['iconscolor']) ? "" : $atts['iconscolor'];
	$atts['weatherIconsColor'] = empty($atts['weathericonscolor']) ? "" : $atts['weathericonscolor'];
	$atts['url_en'] = '';
	$atts['url_es'] = '';
	$atts['isWidget'] = false;
	$instance = WIYCW_default_values($atts);
	return WIYCW_public($instance);
}



function WIYCW_shortcodes_init() {
	add_shortcode('weather_pro', 'WIYCW_shortcode');
}

add_action( 'init', 'WIYCW_shortcodes_init' );

function WIYCW_get_weather(){

	$error = array('error' => true, 'message' => '');

	if(!isset($_GET['id'])){
		$error['message'] = 'ID not set';
		wp_send_json($error);
		die();
	}


	$id = intval(sanitize_text_field($_GET['id']));
	$cache_key = substr('wiycw_'.$id, 0, 44);
	$response = get_transient($cache_key);

	if(empty($response)) {

		$params = array(
		 'id' => $id,
		 's' => get_site_url(),
		 'v' => WIYCW_VERSION,
		 'c' => 'true'
		);

		$url = add_query_arg($params, 'https://eltiempoen.com/api/weather/widget');
	    $arg = array('sslverify' => false, 'timeout' => 10);

	    $request = wp_remote_get($url, $arg);

		if(!is_wp_error($request) && ($request['response']['code'] == 200 || $request['response']['code'] == 201)) {
	       $response = wp_remote_retrieve_body($request);
	       set_transient($cache_key, $response, MINUTE_IN_SECONDS * 30);
		}else{
			if(is_wp_error($request)){
				$error['message'] = $request->get_error_message();
			}else{
				$error['message'] = 'Response code: '.$request['response']['code'];
			}
			
			wp_send_json($error);
			die();
		}

	}

	wp_send_json(json_decode($response));

}

add_action('wp_ajax_WIYCW_get_weather', 'WIYCW_get_weather');
add_action('wp_ajax_nopriv_WIYCW_get_weather', 'WIYCW_get_weather');

?>