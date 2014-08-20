'use strict';
var util = require('util');
var path = require('path');
var yeoman = require('yeoman-generator');
var yosay  = require('yosay');

var WoocommercePaymentGatewayGenerator = yeoman.generators.Base.extend({
  initializing: function () {
    this.pkg = require('../package.json');
    this.currentYear = (new Date()).getFullYear();
  },

  prompting: {
    askForAuthorName: function () {
      var done = this.async();

      this.log(yosay('Create your WooCommerce Payment Gateway Plugin with superpowers!'));

      var prompts = [{
        name: 'authorName',
        message: 'Say me something about you! What\'s your name?',
        default: ''
      }];

      this.prompt(prompts, function (props) {
        this.authorName = props.authorName || 'WooThemes';

        done();
      }.bind(this));
    },

    askForAuthorEmail: function () {
      var done = this.async();

      var prompts = [{
        name: 'authorEmail',
        message: 'Which is your email address?',
        default: ''
      }];

      this.prompt(prompts, function (props) {
        this.authorEmail = props.authorEmail || 'info@woothemes.com';

        done();
      }.bind(this));
    },

    askForAuthorURL: function () {
      var done = this.async();

      var prompts = [{
        name: 'authorURL',
        message: 'Which is your website URL? Leave empty if you do not have one',
        default: ''
      }];

      this.prompt(prompts, function (props) {
        this.authorURL = props.authorURL || 'http://woothemes.com/';

        done();
      }.bind(this));
    },

    askForGatewayName: function () {
      var done = this.async();

      var prompts = [{
        name: 'gatewayName',
        message: 'What\'s the name of your payment gateway?',
        default: 'WooCommerce Payment Gateway'
      }];

      this.prompt(prompts, function (props) {
        this.gatewayName = props.gatewayName;
        this.appname = this._.slugify(this.gatewayName);

        done();
      }.bind(this));
    },

    askForGatewayDescription: function() {
      var done = this.async();

      var prompts = [{
        name: 'gatewayDesc',
        message: 'What does this payment gateway do?',
        default: ''
      }];

      this.prompt(prompts, function (props) {
        this.gatewayDesc = props.gatewayDesc || "Write here your payment gateway description.";

        done();
      }.bind(this));
    },
  },

  configuring: {
    enforceFolderName: function () {
      if (this.appname !== this._.last(this.destinationRoot().split(path.sep))) {
        this.destinationRoot(this.appname);
      }
      this.config.save();
    }
  },

  writing: {
    templates: function () {
      this.template('_woocommerce-payment-gateway.php', this.appname + '.php');
      this.template('_readme.txt', 'readme.txt');
    },

    app: function () {
      this.src.copy('_index.php', 'index.php');
      this.src.copy('_LICENSE', 'LICENSE');
    },

    projectfiles: function () {
      this.src.copy('editorconfig', '.editorconfig');
    }
  },

  end: function () {
    this.log('I did my best to create your WooCommerce Payment Gateway! Not it\'s your turn!');
    this.log('Remember to change the plugin version in readme.txt and ' + this.appname + '.php. Good coding!');
  }
});

module.exports = WoocommercePaymentGatewayGenerator;
