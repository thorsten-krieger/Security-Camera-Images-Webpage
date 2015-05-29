#!/bin/bash

SCRIPT_DIR=${PWD}
SCRIPT_NAME=${0##*/}
SCRIPT_PREFIX=${SCRIPT_NAME%%.sh}

PID_FILE="${SCRIPT_DIR}/${SCRIPT_PREFIX}.pid"
LOG_OUTPUT_FILE="${SCRIPT_DIR}/logs/${SCRIPT_PREFIX}.output.log"
LOG_JOB_FILE="${SCRIPT_DIR}/logs/${SCRIPT_PREFIX}.job.log"
SCRIPT_FILE="${SCRIPT_DIR}/${SCRIPT_PREFIX}.php"


cd ${SCRIPT_DIR}

if [ ! -f ${PID_FILE} ]; then

	echo "################################################## START ##################################################" >> $LOG_OUTPUT_FILE

	pidof -x ${0} > ${PID_FILE}

	/usr/bin/php $SCRIPT_FILE >> $LOG_OUTPUT_FILE

	if [ -f ${PID_FILE} ]; then
		rm ${PID_FILE}
	fi

	echo "################################################## ENDE  ##################################################" >> $LOG_OUTPUT_FILE

fi
