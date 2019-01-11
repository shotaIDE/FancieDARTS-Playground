<?php
// *********************************
// Add inserting shortcodes form element
// *********************************
function dp_sc_add_media_buttons(){
    if (!is_admin()) return;

    global $shortcode_tags;

    $shortcodes_list = '';

    // Copy
    $arr_shortcodes = $shortcode_tags;

    // Exclude shotcodes
    $exclude = array("wp_caption", "embed", "caption", "gallery", "playlist", "audio", "video");
    $add_sc = array('ss','gmaps','recentposts','mostviewedposts','youtube','adsense','qrcode','linkshare','phg', 'accordions', 'toggles', 'tabs', 'table', 'promobox', 'profile', 'highlighter', 'dpslideshow', 'flexbox', 'blogcard', 'talk', 'ptable', 'skillbars', 'skillcircles', 'countup', 'crtable', 'faq', 'txtul', 'txtreveal', 'flipcard', 'capbox');

    foreach ($add_sc as $key => $value) {
        $arr_shortcodes[$value] = $value;
    }

    foreach ($arr_shortcodes as $key => $val){
        if(!in_array($key, $exclude)){
            switch ($key) {
                case 'ss':
                    $val_key = "[".$key." url='' title='Sreenshot Title' caption='This is caption.' ext=1 width=160px hatebu=0 tweets=1 likes=1 class='alignleft' rel=nofollow]\r\n";
                    break;
                case 'gmaps':
                    $val_key = "[".$key." address='' width=100% height=350px title='' text='' zoom=18 hue='' gamma=1 lightness=0 saturation=0 animation=drop]\r\n";
                    break;
                case 'label':
                     $val_key = "[".$key." title='Label Title' color='' icon='' text='Next text' class='']\r\n";
                    break;
                case 'button':
                    $val_key = "[".$key." url='#' title='Button Title' color='' icon='' newwindow='' size='' class='' rel=nofollow]\r\n";
                    break;
                case 'font':
                    $val_key = "[".$key." size='14px' color='' bgcolor='' italic='' class='']Decoration text[/".$key."]\r\n";
                    break;
                case 'qrcode':
                    $val_key = "[".$key." url='' size=200px alt='' class='']\r\n";
                    break;
                case 'adsense':
                    $val_key = "[".$key." id='' unitid='' size='rect']\r\n";
                    break;
                case 'linkshare':
                    $val_key = "[".$key." url='' token='' mid='' title='' price='' cat='' dev='' size='' class='' rel='']\r\n";
                    break;
                case 'phg':
                    $val_key = "[".$key." url='' token='' title='' price='' cat='' dev='' size='' class='' rel='']\r\n";
                    break;
                case 'youtube':
                    $val_key = "[".$key." id='' width='100%' height=350 rel=1]\r\n";
                    break;
                case 'mostviewedposts':
                    $val_key = "[".$key." num=5 thumb=1 term='' views=1 cat='' date=0 year='' month='' hatebu=0 likes=1 tweets=1 ranking=1 excerpt=0]\r\n";
                    break;
                case 'recentposts':
                    $val_key = "[".$key." num=5 cat='' date=0 sort='post_date' hatebu=0 excerpt=0]\r\n";
                    break;
                case 'toggles':
                    $val_key = "[".$key." class='' style='']\r\n[toggle title='Title 1' class='' style='']\r\nFirst Toggle Content\r\n[/toggle]\r\n[toggle title='Title 2' class='' style='']\r\nSecond Toggle Content\r\n[/toggle]\r\n[toggle title='Title 3' class='' style='']\r\nThird Toggle Content\r\n[/toggle]\r\n[/".$key."]\r\n";
                    break;
                case 'accordions':
                    $val_key = "[".$key." class='' style='']\r\n[accordion title='Title 1' class='' style='']\r\nFirst Accordion Content\r\n[/accordion]\r\n[accordion title='Title 2' class='' style='']\r\nSecond Accordion Content\r\n[/accordion]\r\n[accordion title='Title 3' class='' style='']\r\nThird Accordion Content\r\n[/accordion]\r\n[/".$key."]\r\n";
                    break;
                case 'tabs':
                    $val_key = "[".$key." class='']\r\n[tab title='Title 1' class='' icon='']\r\nFirst Tab Content\r\n[/tab]\r\n[tab title='Title 2' class='' icon='']\r\nSecond Tab Content\r\n[/tab]\r\n[tab title='Title 3' class='' icon='']\r\nThird Tab Content\r\n[/tab]\r\n[/".$key."]\r\n";
                    break;
                case 'table':
                    $val_key = "[".$key." width=100% highlight='' hoverrowbgcolor='' hoverrowfontcolor='' hovercellbgcolor='' hovercellfontcolor='' sort='' class='']\r\n[tablehead title='1st col,2nd col,3rd col' class='']\r\n[tablerow title='' align='center' width='' class='' bgcolor='']\r\n[tablecell width='' align='' bgcolor='']\r\nCell 1-1\r\n[/tablecell]\r\n[tablecell width='' align='' bgcolor='']\r\nCell 1-2\r\n[/tablecell]\r\n[tablecell width='' align='' bgcolor='']\r\nCell 1-3\r\n[/tablecell]\r\n[/tablerow]\r\n[tablerow title='' align='center' width='' class='' bgcolor='']\r\n[tablecell width='' align='' bgcolor='']\r\nCell 2-1\r\n[/tablecell]\r\n[tablecell width='' align='' bgcolor='']\r\nCell 2-2\r\n[/tablecell]\r\n[tablecell width='' align='' bgcolor='']\r\nCell 2-3\r\n[/tablecell]\r\n[/tablerow]\r\n[tablerow title='' align='center' width='' class='' bgcolor='']\r\n[tablecell width='' align='' bgcolor='']\r\nCell 3-1\r\n[/tablecell]\r\n[tablecell width='' align='' bgcolor='']\r\nCell 3-2\r\n[/tablecell]\r\n[tablecell width='' align='' bgcolor='']\r\nCell 3-3\r\n[/tablecell]\r\n[/tablerow]\r\n[/tablehead]\r\n[/".$key."]\r\n";
                    break;
                case 'promobox':
                    $val_key = "[".$key." column=3 class='']\r\n[promo title='Promo Title' titlecolor='' titlehovercolor='' titlesize=16px titlebold=1 icon='icon-mobile' iconstyle='square' iconsize=40px iconrotate='' iconscale='' iconcolor='' iconhovercolor='' iconbgcolor='' iconbdcolor='' iconbdwidth='' iconalign='' bgcolor='' bghovercolor='' imgurl='' url='' target='']\r\nPromotion Text Here.\r\n[/promo]\r\n[promo title='Promo Title' titlecolor='' titlehovercolor='' titlesize=16px titlebold=1 icon='icon-laptop' iconstyle='round' iconsize=40px iconrotate='' iconscale='' iconcolor='' iconhovercolor='' iconbgcolor='' iconbdcolor='' iconbdwidth='' iconalign='' bgcolor='' bghovercolor='' imgurl='' url='' target='']\r\nPromotion Text Here.\r\n[/promo]\r\n[promo title='Promo Title' titlecolor='' titlehovercolor='' titlesize=16px titlebold=1 icon='icon-desktop' iconstyle='circle' iconsize=40px iconrotate='' iconscale='' iconcolor='' iconhovercolor='' iconbgcolor='' iconbdcolor='' iconbdwidth='' iconalign='' bgcolor='' bghovercolor='' imgurl='' url='' target='']\r\nPromotion Text Here.\r\n[/promo]\r\n[/".$key."]\r\n";
                    break;
                case 'filter':
                    $val_key = "[".$key." url='' blur='' blurval=4px grayscale='' grayscaleval=100% saturate='' saturateval=0% sepia='' sepiaval=100% brightness='' brightnessval=80% contrast='' contrastval=80% opacity='' opacityval=80% invert='' invertval=100% hue='' hueval=180deg width='' height='' class='' alt='']\r\n";
                    break;
                case 'highlighter':
                    $val_key = "[".$key." type=0 time=3000 fadetime=2000 class='']\r\n[highlight class='' style='']First Highlight Text[/highlight]\r\n[highlight class='' style='']Secound Highlight Text[/highlight]\r\n[highlight class='' style='']Third Highlight Text[/highlight]\r\n[/".$key."]\r\n";
                    break;
                case 'profile':
                    $val_key = "[".$key." name='Your Name' namesize=18px namecolor='' namebold=1 nameitalic='' authorurl='' profimgurl='http://demo.dptheme.net/dp7/wp-content/uploads/sites/2/girl-flowers1-620x422.jpg' profsize=100px profshape=circle profbdwidth=5px topbgimgurl='' topbgcolor=#dddddd bgcolor=#ffffff desccolor=#888888 descfontsize=12px border=1 bdcolor=#dddddd twitterurl='' facebookurl='' googleplusurl='' youtubeurl='' pinteresturl='' width=100% class='']\r\nInsert Your Profile.\r\n[/".$key."]\r\n";
                    break;
                case 'dpslideshow':
                    $val_key = "[".$key." fx='fade' showtime=3500 transitiontime=1200 autoplay=true hoverpause=false showcontrol=true controlpos=center nexttext=Next prevtext=Prev showpagenavi=true pagenavipos=center captionblack=false class='' style='']\r\n[dpslide imgurl='http://demo.dptheme.net/_wp/wp-content/uploads/vegetables.jpg' url='' caption=' This is slide caption.' class='' style='']\r\n<p class='ft22px b white mg30px-top mg20px-l al-c'>You can add the original HTML contents like this.</p>\r\n[/dpslide]\r\n[dpslide imgurl='http://demo.dptheme.net/_wp/wp-content/uploads/vase.jpg' url='' caption=' This is slide caption.' class='' style='']\r\n[/dpslide]\r\n[dpslide imgurl='http://demo.dptheme.net/_wp/wp-content/uploads/tenedores.jpg' url='' caption=' This is slide caption.' class='' style='']\r\n[/dpslide]\r\n[/".$key."]\r\n";
                    break;
                case 'flexbox':
                    $val_key = "[".$key." direction=row wrap=nowrap alignh=left alignv='' alignitems=stretch flexchildren='' width=100% height='' class='' style='']\r\n[flexitem flex=1 margin=10 padding='' width='' height='' class='' style='']\r\nFirst Flex Item\r\n[/flexitem]\r\n[flexitem flex=1 margin=10 padding='' width='' height='' class='' style='']\r\nSecond Flex Item\r\n[/flexitem]\r\n[flexitem flex=1 margin=10 padding='' width='' height='' class='' style='']\r\nThird Flex Item\r\n[/flexitem]\r\n[/".$key."]\r\n";
                    break;
                case 'blogcard':
                    $val_key = "[".$key." url='' width='' height='' class='' style='']";
                    break;
                case 'talk':
                    $val_key = "[".$key." words='' name='' align='' avatarimg='' avatarsize='' avatarshape='' avatarbdwidth='' avatarbdcolor='' avataricon='' color='' bgcolor='' bdcolor='' bdstyle='' class='' style='']";
                    break;
                case 'ptable':
                    $val_key = "[".$key." rowbordered=1 fontsize=13 fontcolor='' class='' style='' plx='']\r\n[ptableitem \r\ntitle='Title Here' \r\ntitlesize=18 \r\ntitlecolor='#fff' \r\ntitlebold=0 \r\ntitlecolor='' \r\ntitlecaption='This is caption' \r\ntitlecaptionsize=12 \r\nprice='&yen;980' \r\npriceper='/ Mo.' \r\npricesize=40 \r\npricebold=0 \r\npricecolor='#fff' \r\npricecaption='This is caption' \r\npricecaptionsize=12 \r\nbutton='Button Title' \r\nbuttonurl='http://' \r\nbuttonsize=18 \r\nbuttontextcolor='#fff' \r\nbuttonbgcolor='' \r\nbuttonbdsize=4 \r\nbuttonext=0 \r\nboxshadow='' \r\nborder=1 \r\nbdcolor='' \r\nrow1='Some great feature' \r\nrow2='Some great feature' \r\nrow3='Some great feature' \r\nrow4='Some great feature' \r\nrow5='Some great feature' \r\nkeycolor='#82DACA' \r\nlabel='' \r\nlabelcolor='' \r\nlabelbgcolor='' \r\nmain='' \r\nclass='' \r\nstyle='']\r\n[ptableitem \r\ntitle='Title Here' \r\ntitlesize=18 \r\ntitlecolor='#fff' \r\ntitlebold=0 \r\ntitlecolor='' \r\ntitlecaption='This is caption' \r\ntitlecaptionsize=12 \r\nprice='&yen;980' \r\npriceper='/ Mo.' \r\npricesize=40 \r\npricebold=0 \r\npricecolor='#fff' \r\npricecaption='This is caption' \r\npricecaptionsize=12 \r\nbutton='Button Title' \r\nbuttonurl='http://' \r\nbuttonsize=18 \r\nbuttontextcolor='#fff' \r\nbuttonbgcolor='' \r\nbuttonbdsize=4 \r\nbuttonext=0 \r\nboxshadow='' \r\nborder=1 \r\nbdcolor='' \r\nrow1='Some great feature' \r\nrow2='Some great feature' \r\nrow3='Some great feature' \r\nrow4='Some great feature' \r\nrow5='Some great feature' \r\nkeycolor='#82DACA' \r\nlabel='' \r\nlabelcolor='' \r\nlabelbgcolor='' \r\nmain='' \r\nclass='' \r\nstyle='']\r\n[ptableitem \r\ntitle='Title Here' \r\ntitlesize=18 \r\ntitlecolor='#fff' \r\ntitlebold=0 \r\ntitlecolor='' \r\ntitlecaption='This is caption' \r\ntitlecaptionsize=12 \r\nprice='&yen;980' \r\npriceper='/ Mo.' \r\npricesize=40 \r\npricebold=0 \r\npricecolor='#fff' \r\npricecaption='This is caption' \r\npricecaptionsize=12 \r\nbutton='Button Title' \r\nbuttonurl='http://' \r\nbuttonsize=18 \r\nbuttontextcolor='#fff' \r\nbuttonbgcolor='' \r\nbuttonbdsize=4 \r\nbuttonext=0 \r\nboxshadow='' \r\nborder=1 \r\nbdcolor='' \r\nrow1='Some great feature' \r\nrow2='Some great feature' \r\nrow3='Some great feature' \r\nrow4='Some great feature' \r\nrow5='Some great feature' \r\nkeycolor='#82DACA' \r\nlabel='' \r\nlabelcolor='' \r\nlabelbgcolor='' \r\nmain='' \r\nclass='' \r\nstyle='']\r\n[/".$key."]\r\n";
                    break;
                case 'skillbars':
                    $val_key = "[".$key." class='' style='' plx='']\r\n[sbar\r\ntitle='Bar title'\r\ntitlecolor='#fff'\r\nratebarcolor=''\r\ntitlesize=12\r\ntitlebold=1\r\nrate=82\r\nratetext=''\r\nratecolor=''\r\nratesize=12\r\nbgcolor='']\r\n[sbar\r\ntitle='Bar title'\r\ntitlecolor='#fff'\r\nratebarcolor=''\r\ntitlesize=12\r\ntitlebold=1\r\nrate=82\r\nratetext=''\r\nratecolor=''\r\nratesize=12\r\nbgcolor='']\r\n[sbar\r\ntitle='Bar title'\r\ntitlecolor='#fff'\r\nratebarcolor=''\r\ntitlesize=12\r\ntitlebold=1\r\nrate=82\r\nratetext=''\r\nratecolor=''\r\nratesize=12\r\nbgcolor='']\r\n[/".$key."]\r\n";
                    break;
                case 'skillcircles':
                    $val_key = "[".$key." class='' style='' plx='']\r\n[scircle\r\nrate=50\r\nratesize=32\r\nratebold=0\r\nbarcolor=''\r\nbarcolor2=''barwidth=''\r\nsize=120\r\nblankcolor='rgba(0,0,0,0.1)'\r\ncaption=''\r\ncaptionsize=13\r\ncaptionbold=0\r\nduration=1200\r\nstartrate=0\r\nstartangle=90\r\nreverse='false']\r\n[scircle\r\nrate=50\r\nratesize=32\r\nratebold=0\r\nbarcolor=''\r\nbarcolor2=''barwidth=''\r\nsize=120\r\nblankcolor='rgba(0,0,0,0.1)'\r\ncaption=''\r\ncaptionsize=13\r\ncaptionbold=0\r\nduration=1200\r\nstartrate=0\r\nstartangle=90\r\nreverse='false']\r\n[scircle\r\nrate=50\r\nratesize=32\r\nratebold=0\r\nbarcolor=''\r\nbarcolor2=''barwidth=''\r\nsize=120\r\nblankcolor='rgba(0,0,0,0.1)'\r\ncaption=''\r\ncaptionsize=13\r\ncaptionbold=0\r\nduration=1200\r\nstartrate=0\r\nstartangle=90\r\nreverse='false']\r\n[/".$key."]\r\n";
                    break;
                case 'countup':
                    $val_key = "[".$key."]\r\n[counter to=1200 tocolor='' tobold=1 caption='Caption' captionsize=13 captionbold=1 captionpos=bottom bgcolor='' bdwidth=0 bdcolor='' bdradius=0 decimal=0 duration=1]\r\n[/".$key."]\r\n";
                    break;
                case 'crtable':
                    $val_key = "[".$key."\r\ncolumn=4\r\nhdtitle='Features'\r\nhdcell1='Users'\r\nhdcell2='Trial'\r\nhdcell3='Storage'\r\nhdcell4='Support'\r\nhdcell5='Price']\r\n\r\n[crtablecol\r\ntitle='Plan 1'\r\ncell1='1 person'\r\ncell2='30 days'\r\ncell3='10GB'\r\ncell4='No support'\r\ncell5='&yen;500/month']\r\n\r\n[crtablecol\r\ntitle='Plan 2'\r\ncell1='5 persons'\r\ncell2='30 days'\r\ncell3='50GB'\r\ncell4='6 months'\r\ncell5='&yen;1,980/month']\r\n\r\n[crtablecol\r\ntitle='Plan 3'\r\ncell1='10 persons'\r\ncell2='30 days'\r\ncell3='100GB'\r\ncell4='One year'\r\ncell5='&yen;3,280/month']\r\n[/".$key."]\r\n";
                    break;
                case 'faq':
                    $val_key = "[".$key."\r\ncat1='Category 1,cat1'\r\ncat2='Category 2,cat2'\r\ncat3='Category 3,cat3'\r\ncat4='Category 4,cat4'\r\ncat5='Category 5,cat5']\r\n\r\n[faqitem\r\ntitle='Question 1(cat1)'\r\ncat=cat1]\r\nAnswer for question 1\r\n[/faqitem]\r\n\r\n[faqitem\r\ntitle='Question 2(cat1)'\r\ncat=cat1]\r\nAnswer for question 2\r\n[/faqitem]\r\n\r\n[faqitem\r\ntitle='Question 3(cat1)'\r\ncat=cat1]\r\nAnswer for question 3\r\n[/faqitem]\r\n\r\n[faqitem\r\ntitle='Question 4(cat2)'\r\ncat=cat2]\r\nAnswer for question 4\r\n[/faqitem]\r\n\r\n[faqitem\r\ntitle='Question 5(cat2)'\r\ncat=cat2]\r\nAnswer for question 5\r\n[/faqitem]\r\n\r\n[faqitem\r\ntitle='Question 6(cat2)'\r\ncat=cat2]\r\nAnswer for question 6\r\n[/faqitem]\r\n\r\n[faqitem\r\ntitle='Question 7(cat3)'\r\ncat=cat3]\r\nAnswer for question 7\r\n[/faqitem]\r\n\r\n[faqitem\r\ntitle='Question 8(cat3)'\r\ncat=cat3]\r\nAnswer for question 8\r\n[/faqitem]\r\n\r\n[faqitem\r\ntitle='Question 9(cat4)'\r\ncat=cat4]\r\nAnswer for question 9\r\n[/faqitem]\r\n\r\n[faqitem\r\ntitle='Question 10(cat4)'\r\ncat=cat4]\r\nAnswer for question 10\r\n[/faqitem]\r\n\r\n[faqitem\r\ntitle='Question 11(cat5)'\r\ncat=cat5]\r\nAnswer for question 11\r\n[/faqitem]\r\n\r\n[faqitem\r\ntitle='Question 12(cat5)'\r\ncat=cat5]\r\nAnswer for question 12\r\n[/faqitem]\r\n[/".$key."]\r\n";
                    break;
                case 'txtul':
                    $val_key = "[".$key." text='Type here' size=15 bold=0 italic=0 color1='#FF7E8B' color2='#FDCAD8' thickness=2]";
                    break;
                case 'txtreveal':
                     $val_key = "[".$key." ltext='Left text' lsize=32 lcolor='#333' litalic=0 lbold=0 rtext=' Right Text' rsize=32 rcolor='#333' ritalic=0 rbold=0 fx=1 time='7' easing='ease']";
                    break;
                case 'flipcard':
                     $val_key = "[".$key."\r\ncolumn=4\r\nhoverfx=1\r\ntxtshadow=1\r\nboxshadow=0\r\nseparaterthick=2\r\nbdradiuslevel=1]\r\n[flipcarditem\r\ntitle='Title here'\r\ntitlesize=18\r\ntitlebold=0\r\ntitleitalic=0\r\ncaption='This is caption'\r\ncaptionsize=12\r\nfronttxtcolor='#fff'\r\nfrontbgcolor=''\r\nfrontimg=''\r\nfrontimgoverlay=1\r\nbacktxtcolor='#fff'\r\nbackbgcolor=''\r\nbackimg=''\r\nbackimgoverlay=1\r\nurl=''\r\nnewwindow=1\r\nplx='enter bottom']\r\nThis is back side content.\r\n[/flipcarditem]\r\n[/".$key."]";
                    break;
                case 'capbox':
                    $val_key = "[".$key."\r\ntitle='Title'\r\ntitlecolor=''\r\ntitlesize=''\r\ntitlepos=left\r\ntitleicon='icon-book-open'\r\ntitlebold=true\r\ntitleitalic=false\r\ntitlepattern=1\r\nbdsize=3\r\nbdstyle=1\r\nbdcolor=''\r\nbgcolor=''\r\ncaptioncolor=''\r\ncaptionsize=''\r\nid=''\r\nclass=''\r\nstyle=''\r\nplx='enter bottom delay 0.8s']\r\nThis is caption strings.\r\n[/".$key."]";
                    break;
                default:
                    $val_key = "[".$key."]\r\n";
                    break;
            }
            $shortcodes_list .= '<option value="'.$val_key.'">'.$key.'</option>';
        }
    }

    $form_code = 
'<select id="dp_sc_select"><option value="">'.__('DP - Shortcodes', DP_SC_PLUGIN_TEXT_DOMAIN).'</option>'.$shortcodes_list.'</select>';

    echo $form_code;
}
add_action('media_buttons', 'dp_sc_add_media_buttons', 11);


// *********************************
// Load javascript and css for admin page
// *********************************
function dp_sc_enqueue_css_js() {
    if (!is_admin()) return;
    $admin_js_url     = plugins_url("../js/admin.min.js", __FILE__);
    wp_enqueue_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
    wp_enqueue_script('dp_sc_admin_js', $admin_js_url, array('jquery'));
}
add_action('admin_print_scripts', 'dp_sc_enqueue_css_js');