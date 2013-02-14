<?php
/**
 * Twitter Options Class
 *
 * @author Jess Green <jgreen@nerdery.com>
 * @category Nerdery_WordPress_Plugins
 * @package Nerdery_Twitter
 */
/**
 * Twitter Options Class
 *
 * @category Nerdery_Twitter_Options
 * @package Nerdery_Twitter
 */
class Nerdery_Twitter_Options
{
    private $option_group_name = "nerdery_twitter_options";

    /**
     * PHP5 Constructor function
     * @return void
     */
    public function __construct()
    {
        add_action("{$this->option_group_name}_option_fields", array($this, 'set_options_fields'), 12);
        add_action("{$this->option_group_name}_option_sections", array($this, 'set_options_sections'), 12);

        add_action('admin_init', array($this, 'options_init'));
        add_action('admin_menu', array($this, 'admin_menu'));

    }

    public function options_init()
    {
        /*
         * register_setting()
         * Settings should be stored as an array in the options table to
         * limit the number of queries made to the DB. The option name should
         * be the same as the option group.
         *
         * Using the options group in a page registered with add_options_page():
         * settings_fields($my_options_class->get_optiongroup_name())
         */
        register_setting(
            $this->option_group_name,
            $this->option_group_name,
            array($this, 'sanitize_options')
        );

        $sections = apply_filters("{$this->option_group_name}_option_sections", array());

        foreach ($sections as $section_name => $data) {
            add_settings_section(
                "{$this->option_group_name}-{$section_name}",
                $data['title'],
                array($this, 'settings_section_cb'),
                "{$this->option_group_name}-{$section_name}"
            );
        }

        $this->output_settings_fields();

    }

    public function admin_menu()
    {
        global $menu_page;

        if (empty($menu_page)){
            $menu_page = add_menu_page(
                __('Twitter Options', NERDERY_TWITTER_DOMAIN),
                __('Twitter Options', NERDERY_TWITTER_DOMAIN),
                'manage_options',
                'nerdery-twitter-options',
                array($this, 'show_menu_page'),
                NERDERY_TWITTER_URLPATH . 'images/logo-16x16.png'
            );
        }

    }

    public function show_menu_page()
    {
        include_once NERDERY_TWITTER_ABSPATH . 'form/form.options.php';
    }
    /**
	 * Output setting fields
	 *
     * @return void
     */
    public function output_settings_fields()
    {

        $field_sections = apply_filters("{$this->option_group_name}_option_fields", array());

        foreach ($field_sections as $field_section => $field) {

            foreach ($field as $field_name => $field_data) {
                add_settings_field(
                    "{$field_section}_options-{$field_data['id']}",
                    (isset($field_data['title']) ? $field_data['title'] : " "),
                    $field_data['callback'],
                    "{$this->option_group_name}-{$field_section}",
                    "{$this->option_group_name}-{$field_section}",
                    array_merge(array('name' => $field_name), $field_data, array('section' => $field_section))
                );
            }
        }

    }

	/**
	 * Returns the options sections.
	 *
	 * @return array
	 */
    public function get_sections()
    {
        return apply_filters("{$this->option_group_name}_option_sections", array());
    }

	/**
	 * Returns the options fields.
	 *
	 * @return array
	 */
    public function get_fields()
    {
        return apply_filters("{$this->option_group_name}_option_fields", array());
    }

	/**
	 * Helper method for setting fields, used to create *_option_fields hook
	 * for other plugins to add fields.
	 *
	 * @return array
	 */
    public function set_options_fields($fields = array())
    {
        $new_options = array(
            'standard'      => array(
                'username'  => array(
                    'id'      => 'twitter-username',
                    'title'   => __('Twitter Username', NERDERY_TWITTER_DOMAIN),
                    'description'   => __('Username of Twitter account to '
                                          . 'display tweets from.',
                                          NERDERY_TWITTER_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'string',
                    'default' => '',
                    'callback' => array($this, 'settings_field_cb')
                ),
                'count'     => array(
                    'id'    => 'tweet-count',
                    'title' => __('# Of Tweets', NERDERY_TWITTER_DOMAIN),
                    'description' => __('Number of tweets to display in feed.',
                                        NERDERY_TWITTER_DOMAIN),
                    'type'  => 'text',
                    'valid' => 'integer',
                    'default' => 5,
                    'callback' => array($this, 'settings_field_cb')
                ),
                'cache_time'     => array(
                    'id'    => 'cache-time',
                    'title' => __('Cache Time', NERDERY_TWITTER_DOMAIN),
                    'description' => __('Cache refresh time in milliseconds',
                                        NERDERY_TWITTER_DOMAIN),
                    'type'  => 'text',
                    'valid' => 'integer',
                    'default' => 1800,
                    'callback' => array($this, 'settings_field_cb')
                )
            ),
            'twitter_oauth' => array(
                'consumer_secret'     => array(
                    'id'    => 'consumer-key',
                    'title' => __('Consumer Secret', NERDERY_TWITTER_DOMAIN),
                    'description' => '',
                    'type'  => 'text',
                    'valid' => 'string',
                    'default' => '',
                    'callback' => array($this, 'settings_field_cb')
                ),
                'consumer_secret_key'     => array(
                    'id'    => 'consumer-secret',
                    'title' => __('Consumer Secret', NERDERY_TWITTER_DOMAIN),
                    'description' => '',
                    'type'  => 'text',
                    'valid' => 'string',
                    'default' => '',
                    'callback' => array($this, 'settings_field_cb')
                ),
                'access_token'     => array(
                    'id'    => 'access-token',
                    'title' => __('Access Token', NERDERY_TWITTER_DOMAIN),
                    'description' => '',
                    'type'  => 'text',
                    'valid' => 'string',
                    'default' => '',
                    'callback' => array($this, 'settings_field_cb')
                ),
                'access_token_key'     => array(
                    'id'    => 'access-token-key',
                    'title' => __('Access Token Key', NERDERY_TWITTER_DOMAIN),
                    'description' => '',
                    'type'  => 'text',
                    'valid' => 'string',
                    'default' => '',
                    'callback' => array($this, 'settings_field_cb')
                ),
            ),
        );

        return array_merge($new_options, (array)$fields);
    }

	/**
	 * Helper method for setting sections, used to create *_option_section hook
	 * for other plugins to add sections.
	 *
	 * @return array
	 */
    public function set_options_sections($sections = array())
    {
        $new_sections = array(
            'standard' => array(
                'title'       => __('Standard Twitter Options', NERDERY_TWITTER_DOMAIN),
                'description' => __("Sets standard Twitter options. "
                                    . "Controls username, cache time and "
                                    . "tweet count.", NERDERY_TWITTER_DOMAIN),
            ),
            'twitter_oauth' => array(
                'title'       => __('Twitter OAuth Options', NERDERY_TWITTER_DOMAIN),
                'description' => __('Set Twitter OAuth options. ', NERDERY_TWITTER_DOMAIN),
            )
        );

        return array_merge($new_sections, (array)$sections);
    }
    /**
     *
     * settings_section_cb()
     * Outputs Settings Sections
     *
     * @param string $section Name of section
     * @return void
     */
    public function settings_section_cb($section)
    {
        $options = $this->get_sections();

        $current = (substr($section['id'], strpos($section['id'], '-') + 1));

        echo "<p>{$options[$current]['description']}</p>";
    }

    /**
     * Output option fields
     *
     * @param mixed $option Current option to output
     * @return string
     */
    public function settings_field_cb($option)
    {

        $option_str    = "";
        $option_values = Nerdery_Twitter_Bootstrap::get_options();

        if ($option['type'] == 'checkbox') {

            $value = !empty($option_values[$option['section']][$option['name']])
                        ? intval($option_values[$option['section']][$option['name']])
                        : 0;

            $option_str = "<label for=\"{$option['id']}\">"
                        . "<input type=\"checkbox\" "
                        . "name=\"{$this->option_group_name}[{$option['section']}]"
                        . "[{$option['name']}]\" "
                        . "id=\"{$option['id']}\" "
                        . "value=\"{$option['default']}\" "
                        . checked($option['default'], $value, false)
                        . " /> {$option['description']}"
                        . "</label>";
        }

        if ($option['type'] == 'text') {
            $description = !empty($option['description'])
                           ? "<span class=\"description\">{$option['description']}</span>" : '';

            $value = empty($option_values[$option['section']][$option['name']])
                        ? $option['default']
                        : sanitize_text_field($option_values[$option['section']][$option['name']]);

            $option_str = "<label for=\"{$option['id']}\">"
                        . "<input type=\"text\" "
                        . "name=\"{$this->option_group_name}[{$option['section']}]"
                        . "[{$option['name']}]\" "
                        . "id=\"{$option['id']}\" "
                        . "value=\"{$value}\" "
                        . " /> {$description}"
                        . "</label>";
        }

        echo $option_str;

    }

	/**
	 * Sanitizes option fields
	 *
	 * @return void
	 */
    public function sanitize_options($options)
    {
		$fields = $this->get_fields();
		$new_options = $options;

		foreach ($options as $option_section => $option) {
			foreach ($option as $option_name => $option_value) {
				$field_data = !empty($fields[$option_section][$option_name])
								? $fields[$option_section][$option_name] : '';

				if ($field_data !== '') {

					switch ($field_data['valid']) {
						case 'boolean' :
						case 'integer':
							$value = is_numeric($option_value) ? intval($option_value) : 0;
						break;

						default:
                            $value = esc_attr($option_value);
					}

					$new_options[$option_section][$option_name] = $value;
				}
			}
		}

        return $new_options;
	}

}

