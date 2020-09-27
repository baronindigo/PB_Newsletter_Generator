(function (win) {
    'use strict';

    var $              = win.jQuery,
        PB             = win.PB || {},
        URL            = '/wp-admin/admin-ajax.php?action=';

        const ActiveCampaign = {

            /**/
            getLists: function (callback) {
                let params = 'pb_get_lists';

                ActiveCampaign.fetchData(params, callback);
            },

            getCampaigns: function(callback) {
                let params = 'pb_get_campaigns';

                ActiveCampaign.fetchData(params, callback);
            },

            getMessages: function(callback) {
                let params = 'pb_get_messages';

                ActiveCampaign.fetchData(params, callback);
            },

            createCampaign: function(data, callback) {
                let params = 'pb_post_campaign';

                ActiveCampaign.postData(params, data, callback);
            },

            createMessage: function(data, callback) {
                let params = 'pb_post_message';

                ActiveCampaign.postData(params, data, callback);
            },

            deleteMessageByID: function(data, callback) {
                let params = 'pb_delete_message';

                ActiveCampaign.postData(params, data, callback);
            },

            viewMessageByID: function(data, callback) {
                let params = 'pb_view_message';

                ActiveCampaign.postData(params, data, callback);
            },

            postData: function(params, data, callback) {
                fetch(URL+params, {
                        method : 'POST',
                        body   : JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        callback(data);
                    })
                    .catch((error) => {
                      console.error('Error: ', error)
                    });
            },

            fetchData: function(params, callback) {
                fetch(URL+params)
                    .then(response => response.json())
                    .then(result => {
                      callback(result);
                    })
                    .catch(error => {
                      console.error('Error:', error);
                    });
            }
        };

    PB.ActiveCampaign = ActiveCampaign;
    win.PB = PB;

}(window));