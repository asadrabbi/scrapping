# scrapping
<h2>Step to Run The Project</h2>
<ul>
<li> First Clone this Project</li>
<li> Set the env file properly </li>
<li> Open Terminal and run following commands</li>
<li> <code>cd docker</code></li>
<li> <code>bin/deploy local</code></li>
<li> <code>cd ../application</code></li>
<li> <code>script/deploy.sh local</code></li>
<li> After Start the project enter the container <code>docker exec -it scrapping-service bash</code></li>
<li> <code>composer install</code></li>
<li> <code>php bin/console doctrine:migrations:migrate</code></li>
<li> Then Logout from container by <code>exit</code></li>
<li> <code>cd ../scrapping</code></li>
<li> <code>bin/deploy.sh local</code></li>
<li> Now set the Domain for run in local</li>
<li> <code>sudo nano /etc/hosts</code></li>
<li> Set <code>127.0.0.1 scrapping.test</code> 
<li> Run on Your Browser by address <a href="scrapping.test">scrapping.test</a> </li>
</ul>

<h2>Explanation</h2>

1. in Docker folder there are all dependency to run this project. We need a Nginx Server, Mysql, Redis, Rabbitmq. After deploy all this dependency will be run through their docker container
2. Application folder is Symfony Project after deploying it will serve the symfony project
3. Finally, We need a service to scrap data, we installed Puppeteer by docker container. I keep this in Scrapping Folder