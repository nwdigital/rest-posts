/***************************************************************************

Copyright (C) 2017 Mathew Moore

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

**************************************************************************/

// Shortcode List Toggle
jQuery(document).ready(function($){
  $('.restpost_edit').each(function(){
    $(this).click(function(){
      $(this).parent().parent().parent().siblings().slideToggle('fast');
    });
  });
});

// asterix next to required fields
jQuery(document).ready(function() {
 jQuery("[required]").after("<span class='required'>*</span>");
});

// Copy Function
function restposts_clip(text) {
    var restposts_copyElement = document.createElement('input');
    restposts_copyElement.setAttribute('type', 'text');
    restposts_copyElement.setAttribute('value', text);
    restposts_copyElement = document.body.appendChild(restposts_copyElement);
    restposts_copyElement.select();
    document.execCommand('copy');
    restposts_copyElement.remove();
}

// Copy Shortcode on Click Event
jQuery(document).ready(function($) {
  $('.mrp_copy_shortcode').each(function(){
    $(this).click(function(){
      shortcode_id = $(this).html();
        restposts_clip(shortcode_id);
    $(this).addClass('mrp_hidden');
    $(this).parent().children('div').fadeIn( 1000, function() {
      // $(this).parent().children('div').children( "span" ).fadeIn( 1000 );
      });
    $(this).parent().children('div').fadeOut( 1000, function() {
      // $(this).parent().children('div').children( "span" ).fadeOut( 1000 );
      $(this).siblings('span').removeClass('mrp_hidden');
      });
    });
  });
});

// delete shortcode check
jQuery(document).ready(function($){
    $(".rp-delete").click(function() {
        if (!confirm("Are you sure you want to delete this shortcode? This process cannot be undone.")){
            return false;
        }
    });
});
