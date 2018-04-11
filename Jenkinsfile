pipeline {
  agent {
    dockerfile {
      dir 'docker/apache'    
    }
  }
  triggers {
    cron '@midnight'
  }
  options {
    buildDiscarder(logRotator(artifactNumToKeepStr: '10'))
  }
  stages {
    stage('distribution') {
      steps {
      	sh 'composer install --no-dev --no-progress'
        sh 'tar -cf p2-website.tar src vendor'
        archiveArtifacts 'p2-website.tar'
      }
    }
    
    stage('test') {
      steps {
      	sh 'composer install --no-progress'
      	sh './vendor/bin/phpunit --log-junit phpunit-junit.xml || exit 0'
      }
      post {
        always {
          junit 'phpunit-junit.xml' 
        }
      }
    }
  }
}