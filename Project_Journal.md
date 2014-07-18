Project Journal
===============

Write little snippets of what was done with this project, dreams and research, issues and resolutions.

> Author: 	David Lighty
> Project: 	Note2Myself clone
> For:		Jason Harrison | BCIT | COMP2920 PHP

---

### Goal

The main site to clone http://note-to-myself.com is a simple note keeper, with the ability to keep text notes, website, and todos.  The goal is to re-create this, using whatever PHP we would prefer.  This is our final project for the class.

#### Personal Vision

I took this and wanted to make something more, bigger, grander....like I do with everything.  It's a canvas to expand upon and Jason is very welcoming of that.  Below here will be small snippets of things in an attempt to highlight what was done.

---

#### Architecture

- Backend : PHP API 
	Slim Framework for a robust and quick approach to an API based system.  Has the ability to easily implement complex routes, middleware and validation.
	> Site: http://www.slimframework.com/

- Backend Data Layer : MongoDB
	Again for a quick and simple db approach that works very well the JSON, we'll use MongoDB.  The data layer is very simple and we don't need many relationships.

- Frontend : BackboneJS 
	To use the API backend, will use the event driven frontend MVC framework BackboneJS.  This allows a nicely data driven approach to consume and use the API backend.
	> Site: http://backbonejs.org/

---

### Features at project submission

1. MongoDB
2. PHP Slim Framework API
3. BackboneJS Frontend
4. "Single Page App" - All that is "served" is index.html
5. User Auth System
	1. Login/Register is forced.
	2. Forgot Password
	3. 3 Attempt User lock system
		1. Lock changes password and provides a secure link to unlock the account.
6. Note taking system
	1. Modular, layered note "views" in backbone
	2. REST API call to get/put/delete with backbone
	3. iframe ajax image uploader
	4. Modal based note selection and creation
	5. LiveURL plugin to detect a web URL.  Auto inputs and validates the website.
		1. Use returned json data for website description (if provided)
7. API call to Bing for the Image of the day
	1. Sets the background to a responsive image.
	2. Sets the footer copyright + link.

---

### Notes/Thoughts

##### Stopping...

What didn't I finish?  The captcha, which would require a seperate register form, not a hard thing to accomplish and I'm sure I could in another day (day == off work half time).  Also didn't do a register email, the code is there for emails on the locking and forgot password, just not this, again it would be part of a register refactoring to a new form.

What else would I have liked to finish?  The error:event system.  The base of it is already in the site, emit a error:event into the vent object and many things can "listen" to it.  Just didn't have the time to finish a UI push for whatever message would be shown.

A search/filter mechanism, to show only a type of note or to return search results.

A hide notes and view background image function.

##### Image limit and display

Finished this, project was to only allow 4 images.  Pushed a css only "thumbnail" style, fixed issue with edit mode.

##### GridFS

Well to support in db image files with MongoDB means that we need to use GridFS.  It is part of PHP with [MongoGridFSFile](http://php.net/manual/en/class.mongogridfsfile.php).  Wow, and it will handle the upload for you just about!!  Skip all the file move, etc.  Just test for supported filetypes or other validation and pass the control name into that class, done.  Very nice.


##### iframe Ajax image uploads

Now supporting image uploads with ajax through a nifty little jQuery plug-in that dynamically creates an iframe to push up the file.


##### Account locking

Added the account locking, not only does this change the user's password, but creates a hashed lock key that must be returned with the user's email to be validated and unlocked again.


##### No sendmail...

So there isn't any sendmail on windows....I need to test email.  Ugh, no smtp on win8...perhaps we'll 'fake' sending the emails.


##### HTML5 Validation issue

I'm supporting validation on the client side with HTML5 validation.  This wasn't working due to an issue with event.preventDefault and Backbone.modal.  The sequence of events for a submit with Backbone.modal is: event.preventDefault -> custom "beforeSubmit" if provided -> if beforeSubmit return false, stop -> run actual submit.  But when I stopped using this method (and thus stopped using event.preventDefault) my HTML5 validation would work, as it was being manually fired in beforeSubmit.  My fix was to move the beforeSubmit in the Backbone.modal code to before preventDefault was fired.  Created a fork on github for this project and added this as a proposed change.  Due to browser support I don't think direct html5 validation should be in directly, rather let the user determine.


##### Note Type Views

Split and create folder/files for each note view type.  Will blend creating a "note view" to pull in the appropriate "type" view.


##### User Auth

Implemented simple User Auth with Slim's encrypted php session support.  Implemented a backbone modal login form [Backbone.Modal](http://awkward.github.io/backbone.modal/)  Only issue I see so far is that re-clicking the same URL/Route doesn't reload like a postback...as you're already on that route.


##### CSS Transitions

Pushed the "quick note" function up into a transitioned element at the top.  Good excerise to learn more about transitions.  Just a little "slide".  Need to make a little transitions library...hmmm.


##### CSS WebGL

I've been working on a side personal project to understand and use [WebGL](https://github.com/davidlighty/3d-Canvas) and I got sidetracking making a neat loading screen while waiting on the MongoLayer to return the notes.


##### MongoLayer and TravisCI

Added a mongo crud layer for the backend.  Found a nice module online and refactored it into a singleton design, used namespaces.  Using namespaces I ran into an issue with using these namespaces correctly in PHP, where I couldn't use global correctly.  Solution was the add "use \<classname>" which solved the issue.

TravisCI was implemented to run all PHP unit tests for the MongoLayer for every Github checkin.


##### Creating the API

Using Slim was pretty easy and straight-forward.  Had some issues with getting the namespacing and autoloader working correctly.


##### Site Init...

Used [Yeoman](http://yeoman.io/) to quickly scaffold out a backbone site.  Yeoman is a great tool to generate all the boilerplate stuff needed for a site.  The community is great and thriving to create custom generators for various types of project.  Requires [npm](https://www.npmjs.org/)

##### Site change...

Originaly started with AngularJS.  Due to various outside forces, I've switched to BackboneJS...  I like both frameworks, but this project will allow me to ramp up my knowledge and experince with BackboneJS.