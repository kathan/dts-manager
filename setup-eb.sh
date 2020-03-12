
set -x
set -e

mkdir ${HOME}/.aws
touch ${HOME}/.aws/config
chmod 600 ${HOME}/.aws/config
echo "[profile eb-cli]" > ${HOME}/.aws/config
echo "aws_access_key_id=$AWS_ACCESS_KEY_ID" >> ${HOME}/.aws/config
echo "aws_secret_access_key=$AWS_SECRET_ACCESS_KEY" >> ${HOME}/.aws/config