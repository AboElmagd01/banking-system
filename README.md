# Dyslexia Detection

>  an application where the fingerprint of the user can be used as authentication. 

## Features
- More effecient and safe way to authenticate.
- The user can withdraw money from his/her account. 
- Users can transfer money to various accounts by stating the account number. 
- A user can view the balance available in his/her respective account. 
- The application also allow the user to view the last transactions.

## Tech
Built in native php and mysql

## Requirements
* [OpenCV](https://opencv.org/)
* Cmake

## Installation
1. Download the repository (project) from the download section or clone it using the following command:
   ```shell
   git clone https://github.com/a-elrawy/dyslexia-detection
   cd dyslexia-detection/
   ```
2. Build and run the app by running the cmake command inside the project folder:
   ```shell
   mkdir build
   cd build
   cmake ../
   make
   ./bin/eyeLike
   ```
