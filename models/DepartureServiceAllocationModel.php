<?php
require_once __DIR__ . "/../commons/function.php";

class DepartureServiceAllocationModel {

    // Lấy tất cả phân bổ dịch vụ của một lịch khởi hành
    public function getByDepartureId($departureId) {
        $sql = "SELECT sa.*,
                td.vehicle_type, td.vehicle_number, td.driver_name, td.driver_phone,
                hd.hotel_name, hd.room_type, hd.room_number, hd.check_in_date, hd.check_out_date,
                fd.flight_number, fd.airline, fd.departure_airport, fd.arrival_airport
                FROM departure_service_allocations sa
                LEFT JOIN departure_transport_details td ON sa.id = td.service_allocation_id AND sa.service_type = 'transport'
                LEFT JOIN departure_hotel_details hd ON sa.id = hd.service_allocation_id AND sa.service_type = 'hotel'
                LEFT JOIN departure_flight_details fd ON sa.id = fd.service_allocation_id AND sa.service_type = 'flight'
                WHERE sa.departure_id = ?
                ORDER BY sa.service_type, sa.start_date, sa.id";
        return pdo_query($sql, $departureId);
    }

    // Lấy một phân bổ theo ID
    public function find($id) {
        $sql = "SELECT sa.*,
                td.vehicle_type, td.vehicle_number, td.driver_name, td.driver_phone, td.capacity, td.route,
                td.pickup_location, td.dropoff_location,
                hd.hotel_name, hd.room_type, hd.room_number, hd.check_in_date, hd.check_out_date,
                hd.check_in_time, hd.check_out_time, hd.number_of_rooms, hd.number_of_nights, hd.amenities,
                fd.flight_number, fd.airline, fd.departure_airport, fd.arrival_airport,
                fd.departure_datetime, fd.arrival_datetime, fd.class, fd.number_of_tickets
                FROM departure_service_allocations sa
                LEFT JOIN departure_transport_details td ON sa.id = td.service_allocation_id AND sa.service_type = 'transport'
                LEFT JOIN departure_hotel_details hd ON sa.id = hd.service_allocation_id AND sa.service_type = 'hotel'
                LEFT JOIN departure_flight_details fd ON sa.id = fd.service_allocation_id AND sa.service_type = 'flight'
                WHERE sa.id = ?";
        return pdo_query_one($sql, $id);
    }

    // Thêm phân bổ dịch vụ
    public function insert($data) {
        $sql = "INSERT INTO departure_service_allocations 
                (departure_id, service_type, service_name, provider_name, provider_contact,
                 booking_reference, start_date, end_date, start_time, end_time, location,
                 quantity, unit, unit_price, total_price, currency, status, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $result = pdo_execute(
            $sql,
            $data['departure_id'],
            $data['service_type'],
            $data['service_name'],
            $data['provider_name'] ?? null,
            $data['provider_contact'] ?? null,
            $data['booking_reference'] ?? null,
            $data['start_date'] ?? null,
            $data['end_date'] ?? null,
            $data['start_time'] ?? null,
            $data['end_time'] ?? null,
            $data['location'] ?? null,
            $data['quantity'] ?? 1,
            $data['unit'] ?? null,
            $data['unit_price'] ?? 0,
            $data['total_price'] ?? 0,
            $data['currency'] ?? 'VND',
            $data['status'] ?? 'pending',
            $data['notes'] ?? null
        );
        
        if ($result) {
            require_once __DIR__ . "/../commons/function.php";
            $serviceId = pdo_last_insert_id();
            
            // Thêm chi tiết theo loại dịch vụ
            if ($data['service_type'] == 'transport' && isset($data['transport_details'])) {
                $this->insertTransportDetails($serviceId, $data['transport_details']);
            } elseif ($data['service_type'] == 'hotel' && isset($data['hotel_details'])) {
                $this->insertHotelDetails($serviceId, $data['hotel_details']);
            } elseif ($data['service_type'] == 'flight' && isset($data['flight_details'])) {
                $this->insertFlightDetails($serviceId, $data['flight_details']);
            }
            
            return $serviceId;
        }
        
        return false;
    }

    // Thêm chi tiết vận chuyển
    private function insertTransportDetails($serviceId, $details) {
        $sql = "INSERT INTO departure_transport_details 
                (service_allocation_id, vehicle_type, vehicle_number, driver_name, driver_phone,
                 license_number, capacity, route, pickup_location, dropoff_location, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        return pdo_execute(
            $sql,
            $serviceId,
            $details['vehicle_type'] ?? null,
            $details['vehicle_number'] ?? null,
            $details['driver_name'] ?? null,
            $details['driver_phone'] ?? null,
            $details['license_number'] ?? null,
            $details['capacity'] ?? null,
            $details['route'] ?? null,
            $details['pickup_location'] ?? null,
            $details['dropoff_location'] ?? null,
            $details['notes'] ?? null
        );
    }

    // Thêm chi tiết khách sạn
    private function insertHotelDetails($serviceId, $details) {
        $sql = "INSERT INTO departure_hotel_details 
                (service_allocation_id, hotel_name, room_type, room_number,
                 check_in_date, check_out_date, check_in_time, check_out_time,
                 number_of_rooms, number_of_nights, amenities, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        return pdo_execute(
            $sql,
            $serviceId,
            $details['hotel_name'] ?? null,
            $details['room_type'] ?? null,
            $details['room_number'] ?? null,
            $details['check_in_date'] ?? null,
            $details['check_out_date'] ?? null,
            $details['check_in_time'] ?? null,
            $details['check_out_time'] ?? null,
            $details['number_of_rooms'] ?? 1,
            $details['number_of_nights'] ?? 1,
            $details['amenities'] ?? null,
            $details['notes'] ?? null
        );
    }

    // Thêm chi tiết vé máy bay
    private function insertFlightDetails($serviceId, $details) {
        $sql = "INSERT INTO departure_flight_details 
                (service_allocation_id, flight_number, airline, departure_airport, arrival_airport,
                 departure_datetime, arrival_datetime, class, number_of_tickets, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        return pdo_execute(
            $sql,
            $serviceId,
            $details['flight_number'] ?? null,
            $details['airline'] ?? null,
            $details['departure_airport'] ?? null,
            $details['arrival_airport'] ?? null,
            $details['departure_datetime'] ?? null,
            $details['arrival_datetime'] ?? null,
            $details['class'] ?? 'economy',
            $details['number_of_tickets'] ?? 1,
            $details['notes'] ?? null
        );
    }

    // Cập nhật phân bổ dịch vụ
    public function update($id, $data) {
        $sql = "UPDATE departure_service_allocations 
                SET service_type = ?, service_name = ?, provider_name = ?, provider_contact = ?,
                    booking_reference = ?, start_date = ?, end_date = ?, start_time = ?, end_time = ?,
                    location = ?, quantity = ?, unit = ?, unit_price = ?, total_price = ?,
                    currency = ?, status = ?, notes = ?
                WHERE id = ?";
        
        $result = pdo_execute(
            $sql,
            $data['service_type'],
            $data['service_name'],
            $data['provider_name'] ?? null,
            $data['provider_contact'] ?? null,
            $data['booking_reference'] ?? null,
            $data['start_date'] ?? null,
            $data['end_date'] ?? null,
            $data['start_time'] ?? null,
            $data['end_time'] ?? null,
            $data['location'] ?? null,
            $data['quantity'] ?? 1,
            $data['unit'] ?? null,
            $data['unit_price'] ?? 0,
            $data['total_price'] ?? 0,
            $data['currency'] ?? 'VND',
            $data['status'] ?? 'pending',
            $data['notes'] ?? null,
            $id
        );
        
        // Cập nhật chi tiết
        if ($result && $data['service_type'] == 'transport' && isset($data['transport_details'])) {
            $this->updateTransportDetails($id, $data['transport_details']);
        } elseif ($result && $data['service_type'] == 'hotel' && isset($data['hotel_details'])) {
            $this->updateHotelDetails($id, $data['hotel_details']);
        } elseif ($result && $data['service_type'] == 'flight' && isset($data['flight_details'])) {
            $this->updateFlightDetails($id, $data['flight_details']);
        }
        
        return $result;
    }

    // Cập nhật chi tiết vận chuyển
    private function updateTransportDetails($serviceId, $details) {
        // Xóa chi tiết cũ
        pdo_execute("DELETE FROM departure_transport_details WHERE service_allocation_id = ?", $serviceId);
        // Thêm mới
        return $this->insertTransportDetails($serviceId, $details);
    }

    // Cập nhật chi tiết khách sạn
    private function updateHotelDetails($serviceId, $details) {
        pdo_execute("DELETE FROM departure_hotel_details WHERE service_allocation_id = ?", $serviceId);
        return $this->insertHotelDetails($serviceId, $details);
    }

    // Cập nhật chi tiết vé máy bay
    private function updateFlightDetails($serviceId, $details) {
        pdo_execute("DELETE FROM departure_flight_details WHERE service_allocation_id = ?", $serviceId);
        return $this->insertFlightDetails($serviceId, $details);
    }

    // Xóa phân bổ dịch vụ
    public function delete($id) {
        $sql = "DELETE FROM departure_service_allocations WHERE id = ?";
        return pdo_execute($sql, $id);
    }
}

