<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

$response['status'] = 'error';

if ( isset( $_POST ) && $_POST ) {
  $name = ( isset( $_POST['name'] ) && $_POST['name'] ) ? trim( strip_tags( $_POST['name'] ) ) : null;
  $email = ( isset( $_POST['email'] ) && $_POST['email'] ) ? trim( strip_tags( $_POST['email'] ) ) : null;
  $subject = ( isset( $_POST['subject'] ) && $_POST['subject'] ) ? trim( strip_tags( $_POST['subject'] ) ) : null;
  $message = ( isset( $_POST['message'] ) && $_POST['message'] ) ? trim( strip_tags( $_POST['message'] ) ) : null;

  if ( is_null( $name ) ) {
    $response['message'] = 'Silahkan isi nama anda';
  } elseif ( is_null( $email ) ) {
    $response['message'] = 'Silahkan isi email anda';
  } elseif ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
    $response['message'] = 'Silahkan isi email yang valid';
  } elseif ( is_null( $subject ) ) {
    $response['message'] = 'Silahkan isi subjek pesan anda';
  } elseif ( is_null( $message ) ) {
    $response['message'] = 'Silahkan isi pesan anda';
  } else {
    $mail_to = get_option( 'primary_email' );

    if ( $mail_to ) {
      $mail_subject = '[' . get_option( 'site_name' ) . '] Pesan Dari Form Kontak';
    	$mail_message = $message;
      $mail_headers = 'From: ' . $email . "\r\n";
      $mail_headers.= 'X-Mailer: PHP/' . phpversion();

      @mail( $mail_to, $mail_subject, $mail_message, $mail_headers );

      $aeo_db->insert( 'messages', [
        'id' => $aeo_db->next_id( 'messages' ),
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message,
        'added_datetime' => time()
      ] );

      $response['status'] = 'success';
      $response['message'] = 'Pesan berhasil dikirim';
    } else {
      $response['message'] = 'Pesan gagal dikirim';
    }
  }
} else {
  $response['message'] = 'No paramaters found';
}

header( "Content-Type: application/json" );

echo json_encode( $response );
