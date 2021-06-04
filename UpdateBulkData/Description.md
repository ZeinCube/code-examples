This module provides updating of filters.
There is a non-standardized set of filters in the database, the class associates them with a standardized value.
The database query selects all non-standardized values for which there is no standard value
Transaction level changes are also used so that the update does not block reading and writing to the database, since the relevance of data is more important than the relevance of filters