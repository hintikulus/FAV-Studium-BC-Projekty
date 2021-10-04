# Class representing object of WORKER
class Worker:

    # Object constructor
    def __init__(self):
        self.workDone = 0
        self.timeWork = 0

    # Function for converting an object to a DOM structure
    def toXML(self, doc=None, id = 0):

        worker_element = doc.createElement('Worker')
        worker_element.setAttribute('id', str(id))

        worker_resources = doc.createElement('resources')
        worker_resources.appendChild(doc.createTextNode(str(self.workDone)))
        worker_element.appendChild(worker_resources)

        worker_work_duration = doc.createElement('workDuration')
        worker_work_duration.appendChild(doc.createTextNode(str(self.timeWork)))
        worker_element.appendChild(worker_work_duration)
        return worker_element
