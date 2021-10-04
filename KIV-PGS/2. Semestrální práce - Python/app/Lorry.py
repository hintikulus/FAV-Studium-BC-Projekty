# Class representing object of LORRY
class Lorry:

    # Object constructor
    def __init__(self):
        self.loadTime = 0
        self.journeyTime = 0

    # Function for converting an object to a DOM structure
    def toXML(self, doc=None, id = 0):

        lorry_element = doc.createElement('Vehicle')
        lorry_element.setAttribute('id', str(id))

        lorry_resources = doc.createElement('loadTime')
        lorry_resources.appendChild(doc.createTextNode(str(self.loadTime)))
        lorry_element.appendChild(lorry_resources)

        lorry_work_duration = doc.createElement('transportTime')
        lorry_work_duration.appendChild(doc.createTextNode(str(self.journeyTime)))
        lorry_element.appendChild(lorry_work_duration)

        return lorry_element


