<?php

function WDFM( $version = 0 ) {
  if ( $version == 2 ) {
    return WDCFM::instance();
  }
  return WDFM::instance();
}