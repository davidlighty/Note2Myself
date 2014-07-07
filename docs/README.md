Notes API
=========

PHP assignment for BCIT 2920 for presentation


###Idea

Create a "twitter" -esque steaming notes system.  This is an API system, which any front end could then use to get and find notes.  People will use this system to create personal notes or ideas that need to be shared with the collective counsiousness.


###Goals

#### PHP Backend
- Slim framework for rapid API development
- phpDocumentor for API documentation
- phpUnit for Unit Testing
- TravisCI (with Github) for Continous Integration testing

#### Data Layer
- MongoDB

#### Web Frontend
- Use Yeoman/Bower/Grunt for the web front end
- Backbone js for front end MVC setup

#### User Auth
- Allow an integrated User auth through Twitter
- Allow a local user auth scenario




#### TravisCI
> https://travis-ci.org/

This is a continous build tool that works on commits into a public github repository.  Browsing the homepage will show a stream of occuring builds on various repos.  Very useful for a team working on a project and they want to make sure that all check-ins always pass their tests.  The tool will then email out pass/fail issues.  

##### To use:
1. Connect to your Github account
2. Connect to a respository
3. Create a .travis.yml file (Add appropriate language and versions!  In whatever language you need, travis supports a very large number)
4. Create tests
5. Commit
6. Get results

Very useful tool that helps test with support for modules and dependencies.
> Additional Information: http://docs.travis-ci.com/user/languages/php/


#### PHPUnit
> http://phpunit.de/getting-started.html

Test suite for code coverage.  Meant for class method testing...