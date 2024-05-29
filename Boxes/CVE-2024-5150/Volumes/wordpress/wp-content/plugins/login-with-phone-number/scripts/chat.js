if(window.makecrispactivate) {
    window.theMessages = {};
    window.picker = '';
    window.$crisp = [];
    window.CRISP_WEBSITE_ID = "99fd6613-af76-4745-80b6-8931ec5e0daa";
    (function () {
        d = document;
        s = d.createElement("script");
        s.src = "https://client.crisp.chat/l.js";
        s.async = 1;
        d.getElementsByTagName("head")[0].appendChild(s);
    })();


    function lwp_setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function lwp_getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }


    function return_answer(c) {
        console.log('picker', c);
        switch (c) {
            case 'How should I config plugin to work?':
                return 'For sending messages or One-Time-Password SMS, config firebase like here:'+"\n"+'https://idehweb.com/?p=3300 '+"\n"+'then create a page, put [idehweb_lwp] inside it, publish it and check it in other browser, or incognito mode or where you are not logged in!';
                break;
            case 'Should I pay money or buy plugin?':
                return 'No, it is totally free! but if you need Vip support or any other pro features, you can buy it $15 in our website:'+'\n' +
                    'https://idehweb.com/?p=3393' +
                    '\n' +
                    'you will get lifetime support and updates, and we can handle installation and set it up for your theme.';
                break;
            case 'I am not receiving OTP!':
                return 'Normally, you have not configured firebase well and this is happening. I suggest: please read help of "configuring firebase" carefully.' + '\n' + 'https://idehweb.com/?p=3300'+"\n"+'did you add your domain with www and none-www version to authorized domain of firebase?'+'\n'+' did you activate phone auth?'+'\n'+' is your server in a location that is supported by firebase?'+'\n'+
                    'please right click on page and click on last option: "inspect" and check console tab to see errors!';
                break;
            case 'I used [idehweb_lwp] shortcode and created a page, but I am not seeing any login form!':
                return 'Bro, I can not show the login/register form to logged in user! so please check it in other browser or where you are not logged in as admin or any other roles!'+'\n'+
                    'by the way, if you know this already, please change your theme and test with other theme! if the problem was relating to your theme, inform me to help and update';
                break;

            case 'Yeah':
                return 'send_ask_question';
                break;
            default:
                return '';
                break;
        }
    }

    function sendFirstQuestions() {
        $crisp.push(["do", "message:show", ["picker", {
            "id": "call-date",
            "text": "Hello my friend, How can I help you?",
            "choices": [
                // {
                //     "value": "1",
                //     "label": "How should I config plugin to work?",
                //     "selected": false
                // },
                // {
                //     "value": "2",
                //     "label": "Should I pay money or buy plugin?",
                //     "selected": false
                // },
                // {
                //     "value": "3",
                //     "label": "I am not receiving OTP!",
                //     "selected": false
                // },
                // {
                //     "value": "4",
                //     "label": "I used [idehweb_lwp] shortcode and created a page, but I am not seeing any login form!",
                //     "selected": false
                // },
                // {
                //     "value": "5",
                //     "label": "Is it compatible with Woocommerce?",
                //     "selected": false
                // },
                // {
                //     "value": "6",
                //     "label": "I need to customize style, what should I do?",
                //     "selected": false
                // },
                // {
                //     "value": "7",
                //     "label": "I need to change text and labels, what should I do?",
                //     "selected": false
                // }
            ]
        }]]);

    }

    function sendAnyQuestions() {

        $crisp.push(["do", "message:show", ["picker", {
            "id": "call-date2",
            "text": "Any other questions...?",
            "choices": [
                {
                    "value": "1",
                    "label": "Yeah",
                    "selected": false
                },
                {
                    "value": "2",
                    "label": "Nope",
                    "selected": false
                }]
        }]
        ]);


    }


    console.log('get cookie...');
    var chatisavailabe = lwp_getCookie('chatisavailabe');
    console.log('chatisavailabe...', chatisavailabe);

    window.CRISP_READY_TRIGGER = function () {
        console.log('CRISP_READY_TRIGGER...');
        jQuery(document).ready(function () {
            "use strict";
            var wehavelink = jQuery('#crisp-chatbox div span:contains("We run on")').parent();
            wehavelink.attr('href', 'https://idehweb.com');
            wehavelink.html('supported by '+'<span style="">idehweb.com</span>');

        });
        console.log('chatisavailabe', chatisavailabe);
        if (chatisavailabe != 'true') {
            $crisp.push(["do", "chat:open"]);
            sendFirstQuestions();
            lwp_setCookie('chatisavailabe', 'true', 1);
        } else {
            $crisp.push(["do", "chat:close"]);

        }
        $crisp.push(["on", "message:compose:sent", function (compose) {
            console.log('compose:sent', compose);
        }]);
        $crisp.push(["on", "message:compose:received", function (compose) {
            console.log('compose:received', compose);
        }]);
        $crisp.push(["on", "message:received", function (message) {
            if (message && message.content && message.content.choices && message.content.choices.length > 0) {
                message.content.choices.forEach(t => {
                    if (t.selected) {
                        if (window.picker != t.label) {
                            window.picker = t.label;
                            var cc = return_answer(window.picker);
                            if (cc == 'send_ask_question') {
                                sendFirstQuestions();
                            } else if (cc == 'user_close_chat') {
                                $crisp.push(["do", "chat:close"]);

                            } else {
                                $crisp.push(["do", "message:show", ["text", cc]]);

                                sendAnyQuestions();
                            }

                        }
                    }

                });
            }
        }]);

    };
}