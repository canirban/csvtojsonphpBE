# csv2jsonphpBE

This repo can be divided into 2 parts

/backend - is a php server exposing one POST api, for CSV to JSON conversion.

/frontend - is a NextJs frontend connecting to the backend php server for getting the converted csv to json.

# Configuration:

Pre-requisites have Node, php installed
I've built the app in macOS and the installation steps are for macOS

1.open a terminal in /backend

2.run php -S localhost:8000

3.open a terminal in /frontend/csv2json

4.run npm i

5.run npm run dev

6.The frontend server will start in port 3000 if no other process is running.

7.Visit http://localhost:3000 fill the CSV field and press submit you'll get the parsed JSON with errors if any.

# Summary

After completing the above steps you will have something like this

FrontEnd NextJs server running on http://localhost:3000

Backend php server running on http://localhost:8000

Now head over to http://localhost:3000 fill the CSV field and press submit you'll get the parsed JSON with errors if any.
