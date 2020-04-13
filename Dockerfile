FROM python:alpine3.7 

COPY ./app/ /app
COPY ./requirements.txt /app/requirements.txt
COPY ./run.py /run.py

RUN pip install -r /app/requirements.txt 
EXPOSE 5000

WORKDIR /
CMD [ "python3", "run.py" ]
