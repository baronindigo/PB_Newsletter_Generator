(function (win) {
    'use strict';

    var $                  = win.jQuery,
        PB                 = win.PB || {},
        ActiveCampaign     = win.PB.ActiveCampaign || {}, 
        ajaxURL            = '/wp-json/wp/v2/posts?_embed',
        sendButton         = document.querySelector('.js-send-form'),
        keyword            = document.querySelector('.js-keyword'),
        searchBtn          = document.querySelector('.js-search-articles'),
        articleList        = document.querySelector('.js-articles-list'),
        newsletterList     = document.querySelector('.js-newsletter-list'),
        listsContainer     = document.querySelector('.js-lists-select'),
        campaignsContainer = document.querySelector('.js-campaigns-select'),
        messagesContainer  = document.querySelector('.js-messages'),
        responseMSG        = document.querySelector('.js-response-msg'),
        campaignSetup      = document.querySelector('.js-campaign-setup'),
        formValues = {
            fromname       : 'js-fromname',
            fromemail      : 'js-fromemail',
            reply2         : 'js-reply2',
            subject        : 'js-subject',
            emailDate      : 'js-email-date',
            emailHour      : 'js-email-hour',
            template       : 'js-template-name',
            list           : 'js-lists-select',
            campaignName   : 'js-campaign-name',
            articlesList   : 'js-newsletter-list .js-article'
        };

        const NewsletterGen = {

            /**/
            init: function () {
                NewsletterGen.addEventListeners();

                // Init Datepickr for Email Date
                new datepickr('featured_date', {'dateFormat': 'm/d/y'});

                if (win.location.hostname == "www.wrestlezone.com") {
                    let path = ajaxURL;
                    ajaxURL  = 'https://www.mandatory.com/wrestlezone' + path;
                }

                // Render Articles
                let url      = ajaxURL + "&filter[posts_per_page]=9&per_page=9",
                    callback = NewsletterGen.renderArticles;
                NewsletterGen.fetchArticles(url, callback);

                ActiveCampaign.getLists(NewsletterGen.renderLists);
                ActiveCampaign.getMessages(NewsletterGen.renderMessages);
                //ActiveCampaign.getCampaigns(NewsletterGen.renderCampaigns);
            },

            /**/
            addEventListeners: function () {
                sendButton.addEventListener('click', NewsletterGen.sendFormValues);

                searchBtn.addEventListener('click', NewsletterGen.searchByKeyword);
                keyword.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        NewsletterGen.searchByKeyword();
                    }
                });
            },

            renderLists: function(data) {
                let lists = data;

                for (let key in lists) {
                    let list = lists[key];

                    let option = document.createElement("OPTION");
                    option.value = list.id;
                    option.label = list.name;

                    listsContainer.append(option);
                }
            },

            renderCampaigns: function(data) {
                let campaigns = data;

                for (let key in campaigns) {
                    let campaign = campaigns[key];

                    let option = document.createElement("OPTION");
                    option.value = campaign.id;
                    option.label = campaign.name;

                    campaignsContainer.append(option);
                }
            },

            renderMessages: function(data) {
                let messages = data;

                for (let key in messages) {
                    let message = messages[key],
                        ID   = message.id,
                        text = '';

                    if (message.subject.length > 215) {
                        text = message.subject.substring(0, 215);
                        text = text + '...';
                    } else {
                        text = message.subject;
                    }

                    messagesContainer.innerHTML += `<div id="js-message-${ID}" class="message" onclick="PB.NewsletterGen.viewMessageByID(${ID})">
                        <div class="content">${text}</div>
                        <div class="cta-btns">
                            <div class="js-add-msg add-btn" data-id="${ID}">O</div>
                            <div class="js-delete-msg delete-btn" data-id="${ID}" onclick="PB.NewsletterGen.deleteMessageByID(${ID})">X</div>
                        </div>
                    </div>`;
                }
            },

            /**/
            searchByKeyword: function () {
                articleList.innerHTML = '';

                let url = ajaxURL + '&search=' + keyword.value;
                let callback = NewsletterGen.renderArticles;

                NewsletterGen.fetchArticles(url, callback);
            },

            /**/
            renderArticles: function (articles) {

                for (let key in articles) {
                    let article = articles[key];

                    let imgDOM = '',
                        img    = '';

                    if (article._embedded['wp:featuredmedia']) {
                        img = article._embedded['wp:featuredmedia'][0].source_url;

                        imgDOM = `<img src="${img}" />`;
                    }

                    let excerpt = article.excerpt.rendered;
                    excerpt = excerpt.replace(/<[^>]*>?/gm, '');

                    let template = 
                        `<li class="js-article-${article.id} js-article" onclick="PB.NewsletterGen.selectedArticle(${article.id})" 
                            data-id="${article.id}" 
                            data-title="${article.title.rendered}" 
                            data-link="${article.link}"
                            data-img="${img}"
                            data-excerpt="${excerpt}">
                            ${imgDOM}
                            <h4>${article.title.rendered}</h4>
                        </li>`;
                    
                    articleList.innerHTML = articleList.innerHTML + template;
                }

            },

            /**/
            fetchArticles: function (url, callback) {
                fetch(url)
                    .then(response => response.json())
                    .then(result => {
                      callback(result);
                    })
                    .catch(error => {
                      console.error('Error:', error);
                    });
            },

            /**/
            deleteMessageByID : function(ID) {
                ActiveCampaign.deleteMessageByID(ID, NewsletterGen.clearForm);
            },

            viewMessageByID : function(ID) {
                ActiveCampaign.viewMessageByID(ID, NewsletterGen.fillForm);
            },

            clearForm : function() {
                document.querySelector(".js-fromname").value  = "";
                document.querySelector(".js-fromemail").value = "";
                document.querySelector(".js-reply2").value = "";
                document.querySelector(".js-subject").value = "";
                document.querySelector(".js-campaign-name").value = "";
                document.querySelector(".js-email-date").value = "";
            },

            fillForm : function(data) {
                document.querySelector(".js-fromname").value  = data.fromname;
                document.querySelector(".js-fromemail").value = data.fromemail;
                document.querySelector(".js-reply2").value = data.reply2;
                document.querySelector(".js-subject").value = data.subject;
            },

            sendFormValues: function () {
                let formParams = {};

                for (let key in formValues) {

                    if (key === 'articlesList') {
                        let DOM = document.querySelectorAll('.'+formValues[key]);
                            formParams[key] = {};

                        DOM.forEach(function (article, index) {
                            formParams[key][index] = {
                                'ID'      : DOM[index].dataset.id,
                                'title'   : DOM[index].dataset.title,
                                'link'    : DOM[index].dataset.link,
                                'img'     : DOM[index].dataset.img,
                                'excerpt' : `${DOM[index].dataset.excerpt}`,
                            };
                        });

                    } else {
                        let DOM = document.querySelector('.'+formValues[key]);
                        formParams[key] = DOM.value;

                        DOM.parentElement.classList.remove('warning');

                        if (DOM.value === "") {
                            DOM.parentElement.classList.add('warning');

                            responseMSG.innerHTML = "Some values from the form are Missing. Please fill the whole form.";
                            return;
                        }
                    }
                }

                ActiveCampaign.createMessage(formParams, NewsletterGen.createCampaign);
            },

            createCampaign: function (data) {
                let formParams = {};

                formParams.messageID = data.id;

                responseMSG.innerHTML = "Message Created. Message ID: "+data.id+'. ';

                for (let key in formValues) {
                    let DOM = document.querySelector('.'+formValues[key]);
                    formParams[key] = DOM.value;
                }

                ActiveCampaign.createCampaign(formParams, NewsletterGen.campaignSuccessfull)
            },

            campaignSuccessfull: function(data) {
                responseMSG.innerHTML += "Campaign Created. Campaign ID: "+data.id;

                NewsletterGen.clearForm();
                newsletterList.innerHTML = "";

            },

            selectedArticle: function (id) {
                let article = document.querySelector('.js-article-'+id);
                article.removeAttribute('onclick');
                article.addEventListener('click', PB.NewsletterGen.removeArticle);

                newsletterList.append(article);
            },

            removeArticle: function () {
                let id = this.dataset.id;
                let article = document.querySelector('.js-article-'+id);

                article.remove();
            }

        };

        NewsletterGen.init();

    PB.NewsletterGen = NewsletterGen;
    win.PB = PB;

}(window));