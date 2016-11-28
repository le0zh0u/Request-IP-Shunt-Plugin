<?php
/*
Plugin Name: Request IP Shunt Plugin
Plugin URI: https://github.com/le0zh0u/Request-IP-Shunt-Plugin
Description: shunt request to domestic server or abroad server by user's ip address
Version: 1.0
Author: leozhou
Author URI: http://leozhou.me/
License: GPLv2
 */

/**
 * 初始化新增一种文章烈性 reuqest_shunt
 */
add_action('init', 'create_request_shunt');

function create_request_shunt() {
  register_post_type('request_shunt',
    array(
      'labels' => array(
          'name' => 'Request Shunt',
          'singular_name' => 'Request Shunt',
          'add_new' => 'Add New',
          'add_new_item' => 'Add New Request Shunt',
          'edit' => 'Edit',
          'edit_item' => 'Edit Request Shunt',
          'new_item' => 'New Request Shunt',
          'view' => 'View',
          'view_item' => 'View Request Shunt',
          'search_items' => 'Search Request Shunt',
          'not_found' => 'No Request Shunt found',
          'not_found_in_trash' => 'Noo Request Shunt fount in Trash',
          'parent' => 'Parent Request Shunt'
      ),
      'public' => true,
      'menu_position' => 15,
      'supports' => array('title', 'editor'),
      'taxonomies' => array(''),
      'menu_icon' => plugins_url('images/image.png', __FILE__),
      'has_archive' => true
    )
  );
}

/**
 * 在文章编辑中添加自定义的元素   domestic_url 和 abroad_url
 */
add_action('admin_init', 'request_shunt_my_admin');

function request_shunt_my_admin(){
  add_meta_box('request_shunt_meta_box', 'Request Shunt Settings', 'display_request_shunt_meta_box', 'request_shunt', 'normal', 'high');
}
/**
 * 构建自定义元素的显示的格式和样式
 * @param  request_shunt $request_shunt request_shunt格式的文章
 * @return html                自定义元素编辑的格式
 */
function display_request_shunt_meta_box($request_shunt){
  $domestic_url = esc_html(get_post_meta($request_shunt->ID, 'domestic_url', true));
  $abroad_url = esc_html(get_post_meta($request_shunt->ID, 'abroad_url', true));
  ?>
  <table>
    <tr>
      <td style="width:100%">
        Domestic URL
      </td>
      <td>
        <input type="text" name="request_shunt_domestic_url" value="<?php echo $domestic_url; ?>" size="80">
      </td>
    </tr>
    <tr>
      <td style="width:100%">
       Abroad URL
      </td>
      <td>
        <input type="text" name="request_shunt_abroad_url" value="<?php echo $abroad_url; ?>" size="80">
      </td>
    </tr>
  </table>
  <?php
}

/**
 * 在保存的同时保存自定义元素
 */
add_action('save_post', 'add_request_shunt_fields', 10, 2);

function add_request_shunt_fields($request_shunt_id, $request_shunt){
  if ($request_shunt->post_type == 'request_shunt') {
    if (isset($_POST['request_shunt_domestic_url']) && $_POST['request_shunt_domestic_url']!='') {
     update_post_meta($request_shunt_id, 'domestic_url', $_POST['request_shunt_domestic_url']);
    }
    if (isset($_POST['request_shunt_abroad_url']) && $_POST['request_shunt_abroad_url']!='') {
     update_post_meta($request_shunt_id, 'abroad_url', $_POST['request_shunt_abroad_url']);
    }
  }
}

/**
 * 加载request_shunt的页面模板
 */
add_filter('template_include', 'include_request_shunt_template_function', 1);
function include_request_shunt_template_function($template_path){
  if(get_post_type() == 'request_shunt'){
    if(is_single()){
      if($theme_file = locate_template(array('single-request_shunt.php'))){
        $template_path = $theme_file;
      }else {
        $template_path = plugin_dir_path(__FILE__) . '/single-request_shunt.php';
      }
    }
  }

  return $template_path;
}
 ?>
