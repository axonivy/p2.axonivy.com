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
        sh 'tar -cf website-file.tar src vendor'
        archiveArtifacts 'website-file.tar'
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
    
     stage('deploy') {
      when {
        branch 'master'
        expression {
          currentBuild.result == null || currentBuild.result == 'SUCCESS' 
        }
      }
      steps {
        sshagent(['3015bfe2-5718-4bd4-9da0-6a5f0169cbfc']) {
          script {
          	def targetFile = "website-file-" + new Date().format("yyyy-MM-dd_HH-mm-ss-SSS");
            def targetFilename =  targetFile + ".tar"

            // copy and unzip
            sh "scp -o StrictHostKeyChecking=no website-file.tar axonivy1@217.26.54.241:/home/axonivy1/deployment/$targetFilename"
            sh "ssh -o StrictHostKeyChecking=no axonivy1@217.26.54.241 mkdir /home/axonivy1/deployment/$targetFile"
            sh "ssh -o StrictHostKeyChecking=no axonivy1@217.26.54.241 tar -xf /home/axonivy1/deployment/$targetFilename -C /home/axonivy1/deployment/$targetFile"
            sh "ssh -o StrictHostKeyChecking=no axonivy1@217.26.54.241 rm -f /home/axonivy1/deployment/$targetFilename"
            
            // create symlinks
            sh "ssh -o StrictHostKeyChecking=no axonivy1@217.26.54.241 ln -fns /home/axonivy1/deployment/$targetFile/src/web /home/axonivy1/www/file.axonivy.rocks/linktoweb"
            sh "ssh -o StrictHostKeyChecking=no axonivy1@217.26.54.241 ln -fns /home/axonivy1/data/p2 /home/axonivy1/deployment/$targetFile/src/web/p2"
          }
        }
      }
    }
    
  }
}