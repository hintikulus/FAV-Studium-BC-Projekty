import getopt
import sys
from datetime import datetime
from xml.dom import minidom as dom

from Lorry import Lorry
from Worker import Worker


# The function parses the input string and obtains the number of milliseconds from it.
def parse_duration(string):
    number = float(string[0:len(string) - 2]) * 1000
    return int(number)


# The function parses the time notation string and obtains the number of elapsed milliseconds from 00:00:00.000
def time_to_milliseconds(string):
    data_time = datetime.strptime(string, '%H:%M:%S.%f')
    result = 0
    result += data_time.hour
    result *= 60
    result += data_time.minute
    result *= 60
    result += data_time.second
    result *= 1000
    result += data_time.microsecond / 1000

    return int(result)


# The main course of the program. The data is read from the file, a DOM tree is created, and the information is saved
# to a file.
def main(argv):
    input_file_path = ''
    output_file_path = ''

    # Message displayed when an error occurs in input arguments
    help_string = 'run_sp2.py -i <input_file_path> -o <output_file_path>'

    ##################################
    # Processing of input parameters #
    ##################################

    try:
        opts, args = getopt.getopt(argv, "hi:o:", ["input=", "output="])
    except getopt.GetoptError:
        print(help_string)
        return

    for opt, arg in opts:
        if opt == '-h':
            print(help_string)
            return
        elif opt in ("-i", "--input"):
            input_file_path = arg
        elif opt in ("-o", "--output"):
            output_file_path = arg

    # Processing of empty inputs
    if len(input_file_path) == 0 or len(output_file_path) == 0:
        print(help_string)
        exit()

    # Preparation of structures for storing information
    workers = {}
    vehicles = {}
    blocks_counter = 0
    blocks_time_sum = 0
    ferry_counter = 0
    ferry_time_sum = 0
    data = None

    ######################
    # Reading input file #
    ######################

    print("Reading input file...");

    # Opening the input file
    input_file = open(input_file_path, "r", encoding="UTF-8")

    # Reading individual lines of the file input file
    for line in input_file:
        data = line.split()  # Dividing the line by spaces

        index = int(data[2])  # Number of the reported object

        # Processing events created by the WORKER object
        if data[1] == "WORKER":
            # Information about the excavated resource
            if data[3] == "mined_source":
                # Add a new WORKER object if it does not exist
                if index not in workers:
                    workers[index] = Worker()

                workers[index].workDone += 1
                workers[index].timeWork += parse_duration(data[4])  # Obtaining the duration of the operation

            # Information about the excavated resource block
            if data[3] == "mined_block":
                blocks_counter += 1
                blocks_time_sum += parse_duration(data[4])  # Obtaining the duration of the operation

        # Processing events created by the LORRY object
        if data[1] == "LORRY":
            if index not in vehicles:
                vehicles[index] = Lorry()

            # Information about the filling of the truck
            if data[3] == "full":
                vehicles[index].loadTime = parse_duration(data[4])  # Obtaining the duration of the operation

            # Arrival information at the ferry
            if data[3] == "arrived_ferry":
                vehicles[index].journeyTime += parse_duration(data[4])  # Obtaining the duration of the operation

            # Information on completion of the journey
            if data[3] == "finished":
                vehicles[index].journeyTime += parse_duration(data[4])  # Obtaining the duration of the operation

        # Processing events created by the FERRY object
        if data[1] == "FERRY":
            # Ferry departure information
            if data[3] == "leaving":
                ferry_counter += 1
                ferry_time_sum += parse_duration(data[4])  # Obtaining the duration of the operation

    # Closing the input file
    input_file.close()

    # Calculation of total data
    sources_counter = 0
    i = 0
    while i < len(workers):
        sources_counter += workers[i].workDone
        i += 1

    #########################################
    # Creating the resulting tree structure #
    #########################################

    print("Creating tree structure")

    # Creating a new DOM document
    doc = dom.Document()

    simulation_element = doc.createElement('Simulation')
    simulation_element.setAttribute('duration', str(time_to_milliseconds(data[0])))

    doc.appendChild(simulation_element)

    blockAvgDur_element = doc.createElement('blockAverageDuration')
    blockAvgDur_element.setAttribute('totalCount', str(blocks_counter))
    blockAvgDur_element.appendChild(doc.createTextNode(str(int((blocks_time_sum / blocks_counter)))))

    simulation_element.appendChild(blockAvgDur_element)

    sourceAvgDur_element = doc.createElement('resourceAverageDuration')
    sourceAvgDur_element.setAttribute('totalCount', str(sources_counter))
    sourceAvgDur_element.appendChild(doc.createTextNode(str(int((blocks_time_sum / sources_counter)))))

    simulation_element.appendChild(sourceAvgDur_element)

    ferryAvgDur_element = doc.createElement('ferryAverageWait')
    ferryAvgDur_element.setAttribute('trips', str(ferry_counter))
    ferryAvgWaitTime = int((ferry_time_sum / ferry_counter))
    ferryAvgDur_element.appendChild(doc.createTextNode(str(ferryAvgWaitTime)))

    simulation_element.appendChild(ferryAvgDur_element)

    workers_element = doc.createElement('Workers')
    vehicles_element = doc.createElement('Vehicles')

    # Listing information about all WORKER objects
    i = 0
    while i < len(workers):
        workers_element.appendChild(workers[i].toXML(doc, i))
        i += 1

    # Listing information about all LORRY objects
    i = 0
    while i < len(vehicles):
        vehicles[i].journeyTime += ferryAvgWaitTime
        vehicles_element.appendChild(vehicles[i].toXML(doc, i))
        i += 1

    simulation_element.appendChild(workers_element)
    simulation_element.appendChild(vehicles_element)

    ##############################
    # Writing to the output file #
    ##############################

    print("Saving tree structure to output file")

    xml_str = doc.toprettyxml()

    with open(output_file_path, "w", encoding="UTF-8") as f:
        f.write(xml_str)
        f.close()

    print("Exporting finished. Data is saved in " + output_file_path)
    print("End of program")
    exit()

# Program entry point
if __name__ == '__main__':
    main(sys.argv[1:])
