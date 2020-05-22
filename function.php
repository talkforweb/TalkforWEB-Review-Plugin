<?php

function talkforweb_get_page($page)
{
    $file = esc_attr(get_option('talforweb_place_id'));
    preg_match('/2m2!(.*?)!/', $file, $match);
    return str_replace($match[1], $page, $file);
}

function talkforweb_save_data($data)
{

    global $wpdb;
    $table = $wpdb->prefix . 'talforweb_review';

    $data = array(
        'user_id' => $data['user_id'],
        'author_name' => $data['author_name'],
        'author_url' => $data['author_url'],
        'profile_photo_url' => $data['profile_photo_url'],
        'rating' => $data['rating'],
        'relative_time_description' => $data['relative_time_description'],
        'text' => $data['text'],
        'time' => $data['time'],
    );
    $format = array(
        '%d',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%d'
    );
    $wpdb->insert($table, $data, $format);

}

function talkforweb_add_review()
{

    $variable = talkforweb_read_review();
    global $wpdb;
    $table = $wpdb->prefix . 'talforweb_review';
    $wpdb->query('TRUNCATE TABLE ' . $table);

    foreach ($variable[2] as $key => $value)
    {

        $value[3] = ($value[3] == '') ? 'N/A' : $value[3];
        $data['author_name'] = $value[0][1];
        $data['author_url'] = $value[0][0];
        $data['profile_photo_url'] = $value[0][2];
        $data['rating'] = $value[4];
        $data['relative_time_description'] = $value[1];
        $data['text'] = $value[3];
        $data['user_id'] = $value[6];
        $data['time'] = time();
        talkforweb_save_data($data);

    }

}

function talkforweb_read_review()
{

    //passing pagination dummy value as paramater
    $page = talkforweb_get_page('45i458');
    $host = 'https://www.google.com/maps/preview/review/listentitiesreviews?authuser=0&hl=en&gl=in&pb=' . $page;
    $response = wp_remote_get( $host );
    $result   = wp_remote_retrieve_body( $response );
    return $result = json_decode(substr($result, 4));
}