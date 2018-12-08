if (typeof GDPRConsent !== 'function') {
    class GDPRConsent
    {
        constructor()
        {
            this.config = {
                'baseURL'       : null,
                'shop'          : null,
                'demo'          : false,
                'canFindUser'   : true,
                'blockedNodes'  : {},
                'pixelAllowed'  : false,
                'history'       : [],
                'hooksInjected' : false,
                'blockables'    : [
                    'connect.facebook.net',
                    'www.google-analytics.com/analytics.js',
                    'www.googletagmanager.com/gtag/js',
                    'static.hotjar.com/c/hotjar',
                    'extreme-dm.com'
                ]
            };
            this.cookieConsent = null;
        }

        inspectConsent()
        {
            let self = this;

            // ----- hook for demo mode, if set, just show message, and return
            if (self.config.demo) { self.askConsent(); return true; }

            // ----- only consider server consent if userId is not null
            let serverConsent = self.user.userId != null && self.config.serverConsent == true ? true : null;

            // ----- if feature not enable or consent accepted, no action
            if (self.config.masterSwitch == false || serverConsent == true) {
                self.allowPixels();
            } else {
                self.askConsent();
            }
        }

        askConsent()
        {
            let self = this;
            let isMobile = false;

            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { isMobile = true; }
            let display = isMobile ? 'block' : 'table-cell';
            let pos = self.config.position + ': 0px;';
            let width = !isMobile ? (self.config.comply == 'browse' ? 'width: 3%;' : 'width: 180px;') : '';

            let templateWrap = document.createElement('div');
            templateWrap.setAttribute('class', 'gdpr-wrapper');
            templateWrap.setAttribute('style', 'background: none '+self.config.candy.bg.hex+';'+pos);
            templateWrap.innerHTML = '<div class="gdpr-container" style="width: 90%;margin: 0px auto; display: table;">' +
                '<div class="gdpr-badge" style="width: 60px; display: table-cell; vertical-align: middle;">' +
                '<img src="'+self.config.baseURL+'/images/gdpr.png" style="height: 45px; vertical-align: middle; padding-right: 20px;"/></div>' +
                '<div class="gdpr-definition" style="display: '+ display +'; color: #fff; font-size: 1.1em; padding: 5px 0px 10px; text-align: justify">'+ self.getDefinition() + '</div>' +
                '<div class="gdpr-actions" style="display: '+ display +'; vertical-align: middle; text-align: right;'+ width +'">' +
                (self.config.comply != 'browse' ? ('<button class="btn btn-success gdpr-accept" style="background: none '+self.config.candy.button.hex+'">Accept</button>') : '') +
                '<img class="gdpr-close" src="'+self.config.baseURL+'/images/close.png" style="filter: brightness(500%); vertical-align: middle; cursor: pointer;"/></div>' +
                '</div>';

            document.querySelector('body').appendChild(templateWrap);

            if (this.config.blockingMode) {
                let veil = document.createElement('div');
                veil.setAttribute('class', 'gdpr-veil');
                document.querySelector('body').appendChild(veil);
                let closeBtn = document.querySelector('.gdpr-close');
                closeBtn.parentNode.removeChild(closeBtn);
            }

            let btnAccept = document.querySelector('.gdpr-accept');
            if (btnAccept != undefined) {
                document.querySelector('.gdpr-accept').addEventListener('click', function(){
                    self.syncConsent('accept');
                    self.allowPixels();

                    let target = document.querySelector('.gdpr-wrapper');
                    target.parentNode.removeChild(target);

                    if (self.config.blockingMode) {
                        let veilElem = document.querySelector('.gdpr-veil');
                        veilElem.parentNode.removeChild(veilElem);
                    }
                });
            }

            let closeBtn = document.querySelector('.gdpr-close');
            if (closeBtn != undefined) {
                document.querySelector('.gdpr-close').addEventListener('click', function(){
                    self.syncConsent('decline');
                    self.blockPixels();

                    let target = document.querySelector('.gdpr-wrapper');
                    target.parentNode.removeChild(target);

                    if (self.config.blockingMode) {
                        let veilElem = document.querySelector('.gdpr-veil');
                        veilElem.parentNode.removeChild(veilElem);
                    }
                });
            }

        }

        syncConsent(decision)
        {
            if (this.config.demo == true) return true;
            this.setConsentCookie(decision);

            /*let i = new Image();
            let url = this.config.baseURL + 'consent/set/' + decision + '/' + this.config.shop + '/' + this.user.userId + '/' + this.user.token + '/' + new Date().getTime();
            i.src = url;*/
        }

        identifyMode()
        {
            let mode = this.getUrlParameter('mode');
            if (mode == 'demo') this.config.demo = true;
        }

        getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            let regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            let results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }

        identifyUser()
        {
            let token = null;
            let self = this;

            // ----- if user is logged in, or accounts are disabled by admin
            if (window.ShopifyAnalytics != undefined
                && window.ShopifyAnalytics.hasOwnProperty('lib')
                && window.ShopifyAnalytics.lib.hasOwnProperty('user')
            ){
                let user = window.ShopifyAnalytics.lib.user().traits();
                token = user.uniqToken;
            } else {
                // ----- check from cookies
                let value = "; " + document.cookie;
                let parts = value.split("; " + '_y' + "=");
                if (parts.length >= 2) token = parts.pop().split(";").shift();
            }

            self.user = {
                'userId' : ShopifyAnalytics.meta.page.customerId  == undefined ? null : ShopifyAnalytics.meta.page.customerId,
                'token' : token
            };
        }

        getConsentCookie()
        {
            let cookie = this.readCookie('GDPRBuster');
            this.cookieConsent = this.config.demo == true ? null : cookie;
        }

        setConsentCookie(decision) {
            let expireDate = new Date();
            expireDate.setMonth(expireDate.getMonth() + 12);

            let consentCookie = {
                'decision'  : decision,
                'shop'     : this.config.shop,
                'userId'    : this.user.userId,
                'token'     : this.user.token
            };

            let user = this.user.userId == null ? '' : this.user.userId;
            document.cookie = 'GDPRBuster'+user+'=' + encodeURIComponent(JSON.stringify(consentCookie)) + '; expires=' + expireDate.toGMTString() + '; path=/';
        }

        allowPixels()
        {
            this.config.pixelAllowed = true;
            this.releasePixels();
        }

        blockPixels()
        {
            this.config.pixelAllowed = false;
        }

        releasePixels()
        {
            let self = this;
            if (self.config.hooksInjected == false) {
                return true;
            }


            for (let source in self.config.blockedNodes) {
                let nodeRef = self.config.blockedNodes[source];
                if (nodeRef.type && source) {
                    let newNode = document.createElement(nodeRef.type);
                    newNode.src = source;
                    if (nodeRef.id) {
                        newNode.id = nodeRef.id;
                    }
                    document.body.appendChild(newNode);
                    self.config.history.push(nodeRef);
                }
            }
            self.config.blockedNodes = {};
        }

        loadPreReqs(callback)
        {
            let self = this;

            // ----- load prerequisites
            let failsafe = 3000; //ms
            let counter = 0;
            let preReqs = setInterval(function(){

                if (window.ShopifyAnalytics) {
                    clearInterval(preReqs);
                    callback();
                } else if (counter >= failsafe) {
                    clearInterval(preReqs);
                    self.config.canFindUser = false;
                    self.allowPixels();
                }
                counter = counter + 50;
            }, 50);

        }

        checkRegion()
        {
            let self = this;
            let writeCookie = false;
            self.euCountries = ['BE', 'BG', 'CZ', 'DK', 'DE', 'EE', 'IE', 'EL',
                'ES', 'FR', 'HR', 'IT', 'CY', 'LV', 'LT', 'LU', 'HU', 'MT', 'NL', 'AT',
                'PL', 'PT', 'RO', 'SI', 'SK', 'FI', 'SE', 'GB'];

            let locationData = self.readCookie('GDPRLocation');
            if (locationData) {
                inspectLocation(locationData)
            } else {
                writeCookie = true;
                self.jax('https://ipapi.co/json/', inspectLocation);
            }

            function inspectLocation(data){
                self.traceData = data;
                if (writeCookie) {
                    let expires = new Date();
                    expires.setTime(expires.getTime()+(12*60*60*1000)); // 12 hours
                    document.cookie = 'GDPRLocation=' + encodeURIComponent(JSON.stringify(self.traceData)) + '; expires=' + expires + '; path=/';
                }

                let isOriginEu = self.euCountries.indexOf(self.traceData.country) != -1;
                // ----- mode is EU only, but user is not EU
                if (self.config.audience == 'eu' && !isOriginEu && !self.config.demo) {
                    self.allowPixels();
                } else {
                    // ----- hook our teeth
                    if (self.config.comply == 'button') {
                        self.injectHooks();
                    }
                    self.inspectConsent();
                }
            }
        }

        jax(url, callback) {
            let self = this;
            let jax = new XMLHttpRequest();
            jax.responseType = 'json';
            jax.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    callback.call(self, this.response);
                }
            };
            jax.open("GET", url, true);
            jax.send();
        }

        injectHooks() {
            // ----- hooks to manage facebook pixels
            let self = this;
            self.gdprInsertBefore = Element.prototype.insertBefore;
            self.gdprAppendChild = Element.prototype.appendChild;

            self.shouldBlock('qeweqw');

            Element.prototype.insertBefore = function () {
                try {
                    if (self.shouldBlock(arguments)) {
                        self.config.blockedNodes[arguments[0].src] = {type: arguments[0].nodeName, id: arguments[0].id, src: arguments[0].src};
                        return;
                    } else {
                        return self.gdprInsertBefore.apply(this, arguments);
                    }
                } catch (error) {
                    //console.warn(error);
                }
            }
            Element.prototype.appendChild = function () {
                try {
                    if (self.shouldBlock(arguments)) {
                        self.config.blockedNodes[arguments[0].src] = {type: arguments[0].nodeName, id: arguments[0].id, src: arguments[0].src};
                        return;
                    } else {
                        return self.gdprAppendChild.apply(this, arguments);
                    }
                } catch (error) {
                    //console.warn(error);
                }
            }
            self.config.hooksInjected = true;

        }

        shouldBlock(args) {
            let self = this;
            let blocked = false;

            for (let i = 0; i < self.config.blockables; i++){
                let blockPath = self.config.blockables[i];

                if (args[0].src && args[0].src.indexOf(blockPath) > -1 && self.config.pixelAllowed == false) {
                    blocked = true;
                    break;
                }
            }

            return blocked;
        }

        removeHooks() {
            Element.prototype.insertBefore = this.gdprInsertBefore;
            Element.prototype.appendChild = this.gdprAppendChild;
        }

        getDefinition() {
            let self = this;
            let browserLang = window.navigator.userLanguage || window.navigator.language;
            let langCode = browserLang.split('-')[0];
            let defaultDefinition = '';
            let langDefinition = '';

            self.config.definitions.forEach(function (def, indx) {
                if (def.lang == 'en') {
                    defaultDefinition = def.definition;
                }
                if (def.lang == langCode) {
                    let langDefinition = def.definition;
                    return true;
                }
            });

            return langDefinition != '' ? langDefinition : defaultDefinition;
        }

        cookieGotAccepted() {
            let self = this;
            return self.cookieConsent != null && self.cookieConsent.decision == 'accept' &&
                self.cookieConsent.shop == self.config.shop && self.config.demo == false;
        }
        cookieGotDeclined() {
            let self = this;
            return self.cookieConsent != null && self.cookieConsent.decision == 'decline' &&
                self.cookieConsent.shop == self.config.shop && self.config.demo == false;
        }

        /**
         * boot sequence of GDPR
         */
        boot() {
            if (window.gdprBooted != undefined) {
                return true;
            }

            window.gdprBooted = true;
            let self = this;

            // ----- load user settings
            Object.assign(self.config, gdpr_buster);

            // ----- identify mode
            self.identifyMode();

            // ----- proceed if demo or enabled
            if (self.config.demo || self.config.masterSwitch) {
                self.loadPreReqs(function (){
                    self.identifyUser();
                    self.getConsentCookie();

                    // ----- agility check, if consent approved, abort
                    if (self.cookieGotAccepted() ||
                        (self.config.comply == 'browse' && self.cookieGotDeclined())
                    ) {
                        self.allowPixels();
                        return true;
                    }
                    // ----- normal flow, check region
                    self.checkRegion();
                });
            }
        }

        readCookie(key) {
            let cookie = null;
            let user = this.user.userId == null ? '' : this.user.userId;
            let match = document.cookie.match(RegExp('(?:^|;\\s*)' + key + user + '=([^;]*)'));

            match = match ? match[1] : null;

            if (match != null) {
                try {
                    cookie = JSON.parse(decodeURIComponent(match));
                } catch (e) { }
            }

            return cookie;
        }
    }

    /**
     * fire it UP !!!!!!
     * @type {GDPRConsent}
     */
    let con = new GDPRConsent();
    con.boot();
}
